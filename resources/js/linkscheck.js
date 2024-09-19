import { utils, messageTypes, elementTypes } from "./healthUtils";

export default async (courseId) => {
    let returnPayload = utils.initPayload();
    
    const courseLinksValidationRes = await utils.apiGet(`/course/${courseId}/linksvalidation`);
    if (courseLinksValidationRes.status != 200) throw new Error("Failed to fetch link validation for course");
    const courseLinksValidation = courseLinksValidationRes.data.result;

    const validationErrors = courseLinksValidation?.results?.issues;
    const hasValidationErrors = validationErrors?.length > 0;
    if (hasValidationErrors) {
        const pageErrors = {};

        for (const issue of validationErrors) {
            const issuePageName = issue.name;
            const issueLinks = issue?.invalid_links ?? [];

            if (!pageErrors[issuePageName]) pageErrors[issuePageName] = [];
            pageErrors[issuePageName] = [...pageErrors[issuePageName], ...issueLinks];
        }

        for (const key in pageErrors) {
            const pageName = key;
            const pageLinks = pageErrors[key];
            const pageChecks = utils.initPayload();

            for (const link of pageLinks) {
                pageChecks.messages.push(utils.payloadMessage(messageTypes.error, elementTypes.links, { _key: "message.links.validation", nb: `(${link?.reason}) ${link?.url}` }));
                pageChecks.messageTypes.error += 1;
            }

            pageChecks.messages = [utils.payloadMessagesWrapper(pageName, "", pageChecks.messages)];
            returnPayload = utils.mergePayloads(returnPayload, pageChecks);
        }
    }

    return returnPayload;
}
