import { utils, messageTypes, elementTypes, validationMessages } from "./healthUtils";

const htmlValidation = {
    heading: (returnPayload, content) => {
        const headers = content.querySelectorAll("h2, h3, h4, h5, h6");
        let startLevel = 1;

        for (const header of headers) {
            const newLevel = Number(header.tagName.substring(1));
            if (newLevel > startLevel + 1) {
                returnPayload.messages.push(utils.payloadMessage(messageTypes.error, elementTypes.heading, validationMessages.heading.order));
                returnPayload.messageTypes.error += 1;
                break;
            }

            returnPayload.messageTypes.success += 1;
            startLevel = newLevel;
        }

        return returnPayload;
    },
    image: (returnPayload, content) => {
        const imgTags = content.querySelectorAll('img');
        for (const img of imgTags) {
            if (img.getAttribute('role') === 'presentation') continue;


            if (img.getAttribute('data-decorative')) {
                returnPayload.messages.push(utils.payloadMessage(messageTypes.warning, elementTypes.image, validationMessages.image.decorative));
                returnPayload.messageTypes.warning += 1;
            } else if (!['.jpg', '.jpeg', '.gif', '.png', '.svg'].includes(img.alt.substring(img.alt.lastIndexOf('.')))) {
                returnPayload.messages.push(utils.payloadMessage(messageTypes.error, elementTypes.image, validationMessages.image.alt));
                returnPayload.messageTypes.error += 1;
            } else if (img.alt.length > 120) {
                returnPayload.messages.push(utils.payloadMessage(messageTypes.warning, elementTypes.image, validationMessages.image.altLong));
                returnPayload.messageTypes.warning += 1;
            } else {
                returnPayload.messageTypes.success += 1;
            }
        }

        return returnPayload;
    },
    table: (returnPayload, content) => {
        const tables = content.querySelectorAll('table');
        for (const table of tables) {
            if (!table.querySelector('caption')) {
                returnPayload.messages.push(utils.payloadMessage(messageTypes.error, elementTypes.table, validationMessages.table.caption));
                returnPayload.messageTypes.error += 1;
            } else {
                returnPayload.messageTypes.success += 1;
            }

            if (!table.querySelector('th')) {
                returnPayload.messages.push(utils.payloadMessage(messageTypes.error, elementTypes.table, validationMessages.table.title));
                returnPayload.messageTypes.error += 1;
            } else {
                returnPayload.messageTypes.success += 1;
            }
        }

        return returnPayload;
    },
    style: (returnPayload, content) => {
        if (typeof content === 'string' && content.includes('style=')) {
            returnPayload.messages.push(utils.payloadMessage(messageTypes.warning, elementTypes.style, validationMessages.style.custom));
            returnPayload.messageTypes.warning += 1;
        } else {
            returnPayload.messageTypes.success += 1;
        }

        return returnPayload;
    }
}

const uucheckUtils = {
    htmlValidation: (returnPayload, html) => {
        const content = document.createElement('div');
        content.innerHTML = html;

        returnPayload = htmlValidation.heading(returnPayload, content);
        returnPayload = htmlValidation.image(returnPayload, content);
        returnPayload = htmlValidation.table(returnPayload, content);
        returnPayload = htmlValidation.style(returnPayload, html);

        return returnPayload;
    },
}

const contentTypeChecks = {
    page: async (coursePage, courseId) => {
        const returnPayload = utils.initPayload();
        returnPayload.contentTypes.pages += 1;

        const isMissingId = !coursePage._id;
        if (isMissingId) coursePage._id = coursePage.page_id;

        const coursePageDataRes = await utils.apiGet(`/course/${courseId}/coursepage/${coursePage._id}`);
        const coursePageData = coursePageDataRes.data.result;
        if (!coursePageData) return returnPayload;

        return uucheckUtils.htmlValidation(returnPayload, coursePageData);
    },
    assignment: (coursePage) => {
        const returnPayload = utils.initPayload();
        returnPayload.contentTypes.assignments += 1;

        return uucheckUtils.htmlValidation(returnPayload, coursePage.description);
    },
    discussion: (coursePage) => {
        const returnPayload = utils.initPayload();
        returnPayload.contentTypes.discussions += 1;

        return uucheckUtils.htmlValidation(returnPayload, coursePage.message);
    }
}

export default async (courseId) => {
    let returnPayload = utils.initPayload();
    
    const modulesList = await utils.getModulesList(courseId);
    const modulesListHasLength = modulesList.length > 0;
    if (!modulesListHasLength) return returnPayload;

    for (const moduleListItem of modulesList) {
        for (const moduleItem of moduleListItem.moduleItems) {
            const moduleItemId = moduleItem.content._id;
            if (!moduleItemId) continue;

            const moduleItemContent = moduleItem?.content;
            if (moduleItemContent.description !== undefined) {
                const assignmentChecks = contentTypeChecks.assignment(moduleItemContent);
                if (assignmentChecks.messages.length > 0) assignmentChecks.messages = [utils.payloadMessagesWrapper(moduleItemContent.name, "", assignmentChecks.messages)];

                returnPayload = utils.mergePayloads(returnPayload, assignmentChecks);
            } else if (moduleItemContent.message !== undefined) {
                const discussionChecks = contentTypeChecks.discussion(moduleItemContent);
                if (discussionChecks.messages.length > 0) discussionChecks.messages = [utils.payloadMessagesWrapper(moduleItemContent.title, "", discussionChecks.messages)];

                returnPayload = utils.mergePayloads(returnPayload, discussionChecks);
            } else {
                const pageChecks = await contentTypeChecks.page(moduleItemContent, courseId);
                if (pageChecks.messages.length > 0) pageChecks.messages = [utils.payloadMessagesWrapper(moduleItemContent.title, "", pageChecks.messages)];

                returnPayload = utils.mergePayloads(returnPayload, pageChecks);
            }
        }
    }

    return returnPayload;
}
