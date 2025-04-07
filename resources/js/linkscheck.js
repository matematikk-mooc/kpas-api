import { utils, messageTypes, elementTypes } from "./healthUtils";

export default async (courseId) => {
  let returnPayload = utils.initPayload();

  let courseLinksValidationRes;
  const startLinksValidationRes = await utils.apiPost(
    `/course/${courseId}/linksvalidation`
  );
  if (startLinksValidationRes?.data?.result?.success == true) {
    let workflowState = startLinksValidationRes?.data?.result?.workflow_state;
    while (workflowState != "completed") {
      await new Promise((resolve) => setTimeout(resolve, 5000));
      courseLinksValidationRes = await utils.apiGet(
        `/course/${courseId}/linksvalidation`
      );
      if (courseLinksValidationRes.status != 200)
        throw new Error("Failed to fetch link validation for course");
      workflowState = courseLinksValidationRes?.data?.result?.workflow_state;
    }
  }

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
        pageChecks.messages.push(
          utils.payloadMessage(messageTypes.error, elementTypes.links, {
            _key: "message.links.validation",
            nb: `(${link?.reason}) ${link?.url}`,
          })
        );
        pageChecks.messageTypes.error += 1;
      }

      pageChecks.messages = [
        utils.payloadMessagesWrapper(pageName, "", pageChecks.messages),
      ];
      returnPayload = utils.mergePayloads(returnPayload, pageChecks);
    }
  }

  return returnPayload;
};
