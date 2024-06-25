import api from "./api";

export default async (content, courseId) => {
    let returnPayload = utils.initPayload();

    const courseModules = content.data.course.modulesConnection.nodes
    const courseModulesHasLength = courseModules.length > 0;
    if (!courseModulesHasLength) {
        const coursePagesRes = await utils.apiGet(`/course/${courseId}/coursepages`);
        const coursePages = coursePagesRes.data.result;

        const coursePagesHasLength = coursePages.length > 0;
        if (!coursePagesHasLength) return returnPayload;

        for (const pageItem of coursePages) {
            const pageChecks = await contentTypeChecks.page(pageItem, courseId);
            if (pageChecks.messages.length > 0) pageChecks.messages = [utils.payloadMessagesWrapper(pageItem.title, pageItem.html_url, pageChecks.messages)];

            returnPayload = utils.mergePayloads(returnPayload, pageChecks);
        }

        return returnPayload;
    }

    const courseDataRes = await utils.apiGet(`/course/${courseId}/coursedata`);
    const courseData = courseDataRes.data.result;

    if (courseData.default_view == 'wiki') {
        const coursePagesRes = await utils.apiGet(`/course/${courseId}/coursepages`);
        const coursePages = coursePagesRes.data.result;

        for (const coursePagesItem of coursePages) {
            if (coursePagesItem.front_page) {
                const pageChecks = await contentTypeChecks.page(coursePagesItem, courseId);
                console.log("FRONTPAGE_CHECK", pageChecks, returnPayload);
                if (pageChecks.messages.length > 0) pageChecks.messages = [utils.payloadMessagesWrapper(coursePagesItem.title, coursePagesItem.html_url, pageChecks.messages)];
                returnPayload = utils.mergePayloads(returnPayload, pageChecks);
                break
            }
        }
    }

    for (const courseModulesItem of courseModules) {
        for (const moduleItemsItem of courseModulesItem.moduleItems) {
            if (!moduleItemsItem.content._id) continue;

            if (moduleItemsItem.content.description) {
                const assignmentChecks = contentTypeChecks.assignment(moduleItemsItem.content);
                console.log("ASSIGNMENT_CHECK", discussionChecks, returnPayload);
                if (assignmentChecks.messages.length > 0) assignmentChecks.messages = [utils.payloadMessagesWrapper(moduleItemsItem.content.name, "", assignmentChecks.messages)];
                returnPayload = utils.mergePayloads(returnPayload, assignmentChecks);
            } else if (moduleItemsItem.content.message) {
                const discussionChecks = contentTypeChecks.discussion(moduleItemsItem.content);
                console.log("DISCUSSION_CHECK", discussionChecks, returnPayload);
                if (discussionChecks.messages.length > 0) discussionChecks.messages = [utils.payloadMessagesWrapper(moduleItemsItem.content.title, "", discussionChecks.messages)];
                returnPayload = utils.mergePayloads(returnPayload, discussionChecks);
            } else {
                const pageChecks = await contentTypeChecks.page(moduleItemsItem.content, courseId);
                console.log("PAGE_CHECK", pageChecks, returnPayload);
                if (pageChecks.messages.length > 0) pageChecks.messages = [utils.payloadMessagesWrapper(moduleItemsItem.content.title, "", pageChecks.messages)];
                returnPayload = utils.mergePayloads(returnPayload, pageChecks);
            }
        }


    }

    return returnPayload;
}

export const utils = {
    apiGet: async (last_part_of_url) => {
        return await api.get(location.origin + '/api' + last_part_of_url, {
            params: {
                cookie: window.cookie
            }
        });
    },
    initPayload: () => {
        return {
            contentTypes: { pages: 0, assignments: 0, discussions: 0 },
            messageTypes: { error: 0, contrast: 0, warning: 0, note: 0, success: 0 },
            messages: []
        };
    },
    payloadMessagesWrapper: (title, href, messages = []) => {
        return { title, href, messages };
    },
    payloadMessage: (type, element, message) => {
        return { type, element, message }
    },
    mergePayloads: (payload1, payload2) => {
        const mergedContentTypes = {
            pages: (payload1.contentTypes.pages || 0) + (payload2.contentTypes.pages || 0),
            assignments: (payload1.contentTypes.assignments || 0) + (payload2.contentTypes.assignments || 0),
            discussions: (payload1.contentTypes.discussions || 0) + (payload2.contentTypes.discussions || 0)
        };

        const mergedMessageTypes = { ...payload1.messageTypes };
        for (const key in payload2.messageTypes) {
            if (mergedMessageTypes.hasOwnProperty(key)) {
                mergedMessageTypes[key] += payload2.messageTypes[key];
            } else {
                mergedMessageTypes[key] = payload2.messageTypes[key];
            }
        }

        return {
            contentTypes: mergedContentTypes,
            messageTypes: mergedMessageTypes,
            messages: [...payload1.messages, ...payload2.messages]
        };
    },
    htmlValidation: (returnPayload, html) => {
        const content = document.createElement('div');
        content.innerHTML = html;

        returnPayload = htmlValidation.heading(returnPayload, content);
        returnPayload = htmlValidation.image(returnPayload, content);
        returnPayload = htmlValidation.table(returnPayload, content);
        returnPayload = htmlValidation.style(returnPayload, html);

        return returnPayload;
    }
};

export const htmlValidation = {
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

export const contentTypeChecks = {
    page: async (coursePage, courseId) => {
        const returnPayload = utils.initPayload();
        returnPayload.contentTypes.pages += 1;

        const isMissingId = !coursePage._id;
        if (isMissingId) coursePage._id = coursePage.page_id;

        const coursePageDataRes = await utils.apiGet(`/course/${courseId}/coursepage/${coursePage._id}`);
        const coursePageData = coursePageDataRes.data.result;
        if (!coursePageData) return returnPayload;

        return utils.htmlValidation(returnPayload, coursePageData);
    },
    assignment: (coursePage) => {
        const returnPayload = utils.initPayload();
        returnPayload.contentTypes.assignments += 1;

        return utils.htmlValidation(returnPayload, coursePage.description);
    },
    discussion: (coursePage) => {
        const returnPayload = utils.initPayload();
        returnPayload.contentTypes.discussions += 1;

        return utils.htmlValidation(returnPayload, coursePage.message);
    }
}

export const messageTypes = {
    error: { _color: "#e31b0c", _key: "error", nb: "Feil" },
    contrast: { _color: "#f88078", _key: "contrast", nb: "Kontrastfeil" },
    warning: { _color: "#ff9800", _key: "warning", nb: "Advarsler" },
    note: { _color: "#e0e0e0", _key: "note", nb: "Merknader" },
    success: { _color: "#3b873e", _key: "success", nb: "Bra!" },
};

export const elementTypes = {
    heading: { _key: "heading", nb: "Overskrift" },
    image: { _key: "image", nb: "Bilde" },
    table: { _key: "table", nb: "Tabell" },
    style: { _key: "style", nb: "Stil" }
};

export const validationMessages = {
    heading: {
        order: { _key: "message.heading.order", nb: "Feil i hierarkiet." }
    },
    image: {
        decorative: { _key: "message.image.decorative", nb: "Merket som dekorativt på den gamle ustandardiserte måten." },
        alt: { _key: "message.image.alt", nb: "Må markeres som dekorativt eller få alternativ tekst som ikke er filnavnet." },
        altLong: { _key: "message.image.altLong", nb: "Alternativ tekst er lengre enn den anbefalte grensen på 120 tegn." }
    },
    table: {
        caption: { _key: "message.table.caption", nb: "Mangler overskrift." },
        title: { _key: "message.table.title", nb: "Mangler titler på rader og/eller kolonner." }
    },
    style: {
        custom: { _key: "message.style.custom", nb: "Sjekk om det brukes for å lage overskrift, ødelegger kontrast eller fører til andre problemer." }
    }
};
