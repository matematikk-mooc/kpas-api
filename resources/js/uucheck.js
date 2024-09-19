import { utils, contentTypeChecks } from "./healthUtils";

export default async (courseId) => {
    let returnPayload = utils.initPayload();
    
    const modulesList = await utils.getModulesList(courseId);
    const modulesListHasLength = modulesList.length > 0;
    if (!modulesListHasLength) return returnPayload;

    for (const moduleListItem of modulesList) {
        for (const moduleItem of moduleListItem.moduleItems) {
            const moduleItemId = moduleItem.content._id;
            if (!moduleItemId) continue;

            if (moduleItem.content.description) {
                const assignmentChecks = contentTypeChecks.assignment(moduleItem.content);
                console.log("ASSIGNMENT_CHECK", moduleItemId, assignmentChecks, returnPayload);
                if (assignmentChecks.messages.length > 0) assignmentChecks.messages = [utils.payloadMessagesWrapper(moduleItem.content.name, "", assignmentChecks.messages)];
                returnPayload = utils.mergePayloads(returnPayload, assignmentChecks);
            } else if (moduleItem.content.message) {
                const discussionChecks = contentTypeChecks.discussion(moduleItem.content);
                console.log("DISCUSSION_CHECK", moduleItemId,  discussionChecks, returnPayload);
                if (discussionChecks.messages.length > 0) discussionChecks.messages = [utils.payloadMessagesWrapper(moduleItem.content.title, "", discussionChecks.messages)];
                returnPayload = utils.mergePayloads(returnPayload, discussionChecks);
            } else {
                const pageChecks = await contentTypeChecks.page(moduleItem.content, courseId);
                console.log("PAGE_CHECK", moduleItemId, pageChecks, returnPayload);
                if (pageChecks.messages.length > 0) pageChecks.messages = [utils.payloadMessagesWrapper(moduleItem.content.title, "", pageChecks.messages)];
                returnPayload = utils.mergePayloads(returnPayload, pageChecks);
            }
        }
    }

    return returnPayload;
}
