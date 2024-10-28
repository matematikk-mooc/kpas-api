import { utils, messageTypes, elementTypes, validationMessages } from "./healthUtils";

// ?NOTE: Missing support for quiz and file content type settings
const sanityValidation = {
    missingModules: (courseChecks) => {
        let returnPayload = utils.initPayload();

        courseChecks.messages.push(utils.payloadMessage(messageTypes.error, elementTypes.settings, validationMessages.course.noModules));
        courseChecks.messageTypes.error += 1;

        courseChecks.messages = [utils.payloadMessagesWrapper("", "", courseChecks.messages)];
        returnPayload = utils.mergePayloads(returnPayload, courseChecks);

        return returnPayload;
    },

    isModulesPublished: (moduleChecks, module, modulesData) => {
        const currentModuleData = modulesData.find((moduleData) => {
            if (moduleData.id == module._id) return moduleData.published;
            return false;
        });

        const isModulePublished = !currentModuleData || currentModuleData?.published != true;
        const isModulePublishedType = isModulePublished ? 'error' : 'success';
        moduleChecks.messages.push(utils.payloadMessage(messageTypes[isModulePublishedType], elementTypes.module, validationMessages.course.moduleNotPublished));
        moduleChecks.messageTypes[isModulePublishedType] += 1;

        return moduleChecks;
    },
    isModuleItemsPublished: (moduleItemChecks, moduleItemData) => {
        const isModuleItemPublished = !moduleItemData || moduleItemData?.published != true;
        const isModuleItemPublishedType = isModuleItemPublished ? 'error' : 'success';
        moduleItemChecks.messages.push(utils.payloadMessage(messageTypes[isModuleItemPublishedType], elementTypes.content, validationMessages.course.moduleItemNotPublished));
        moduleItemChecks.messageTypes[isModuleItemPublishedType] += 1;

        return moduleItemChecks;
    },

    validPageSettings: (moduleItemChecks, moduleItemData) => {
        if (!moduleItemData) return moduleItemChecks;
        const missingRequirement = moduleItemData?.completion_requirement?.type == null;
        const missingRequirementType = moduleItemData?.completion_requirement?.type != 'must_mark_done';

        const missingRequirementMessageType = missingRequirement ? 'error' : 'success';
        moduleItemChecks.messages.push(utils.payloadMessage(messageTypes[missingRequirementMessageType], elementTypes.content, validationMessages.content.missingRequirement));
        moduleItemChecks.messageTypes[missingRequirementMessageType] += 1;

        const missingRequirementTypeMessageType = missingRequirementType ? 'warning' : 'success';
        moduleItemChecks.messages.push(utils.payloadMessage(messageTypes[missingRequirementTypeMessageType], elementTypes.content, validationMessages.content.missingRequirementMarkAsDone));
        moduleItemChecks.messageTypes[missingRequirementTypeMessageType] += 1;        

        return moduleItemChecks;
    },
    validAssignmentSettings: (moduleItemChecks, moduleItemData, assignmentData) => {
        if (!moduleItemData || !assignmentData) return moduleItemChecks;
        const missingRequirement = moduleItemData?.completion_requirement?.type == null;
        const missingRequirementType = moduleItemData?.completion_requirement?.type != 'must_submit';
        const missingSubmissionType = assignmentData?.submission_types?.includes('online_upload') && assignmentData?.allowed_extensions == null;
        const missingDueDate = assignmentData?.due_at == null;
        const missingPeerReviewType = assignmentData?.peer_reviews == true && assignmentData?.automatic_peer_reviews == true;
        const hasPeerReviewType = assignmentData?.peer_reviews == true && assignmentData?.automatic_peer_reviews == false;

        const missingRequirementMessageType = missingRequirement ? 'error' : 'success';
        moduleItemChecks.messages.push(utils.payloadMessage(messageTypes[missingRequirementMessageType], elementTypes.content, validationMessages.content.missingRequirement));
        moduleItemChecks.messageTypes[missingRequirementMessageType] += 1;

        const missingRequirementTypeMessageType = missingRequirementType ? 'warning' : 'success';
        moduleItemChecks.messages.push(utils.payloadMessage(messageTypes[missingRequirementTypeMessageType], elementTypes.content, validationMessages.content.missingRequirementSubmit));
        moduleItemChecks.messageTypes[missingRequirementTypeMessageType] += 1;

        const missingSubmissionTypeMessageType = !missingRequirementType && missingSubmissionType ? 'error' : 'success';
        moduleItemChecks.messages.push(utils.payloadMessage(messageTypes[missingSubmissionTypeMessageType], elementTypes.content, validationMessages.content.missingSubmissionFileTypes));
        moduleItemChecks.messageTypes[missingSubmissionTypeMessageType] += 1; 

        const missingDueDateMessageType = missingDueDate ? 'warning' : 'success';
        moduleItemChecks.messages.push(utils.payloadMessage(messageTypes[missingDueDateMessageType], elementTypes.content, validationMessages.content.missingDueDate));
        moduleItemChecks.messageTypes[missingDueDateMessageType] += 1;

        const missingPeerReviewTypeMessageType = missingPeerReviewType ? 'warning' : 'success';
        moduleItemChecks.messages.push(utils.payloadMessage(messageTypes[missingPeerReviewTypeMessageType], elementTypes.content, validationMessages.content.missingPeerReviewType));
        moduleItemChecks.messageTypes[missingPeerReviewTypeMessageType] += 1;

        const hasPeerReviewTypeMessageType = !missingPeerReviewType && hasPeerReviewType ? 'note' : 'success';
        moduleItemChecks.messages.push(utils.payloadMessage(messageTypes[hasPeerReviewTypeMessageType], elementTypes.content, validationMessages.content.missingPeerReviewDueDateReminder));

        return moduleItemChecks;
    },
    validDiscussionSettings: (moduleItemChecks, moduleItemData, discussionTopic) => {
        if (!moduleItemData || !discussionTopic) return moduleItemChecks;
        const missingRequirement = moduleItemData?.completion_requirement?.type == null;
        const missingRequirementType = moduleItemData?.completion_requirement?.type != 'must_contribute';
        const missingDiscussionType = discussionTopic?.discussion_type != 'threaded';
        const missingGroupCategory = discussionTopic?.group_category_id == null;

        const missingRequirementMessageType = missingRequirement ? 'error' : 'success';
        moduleItemChecks.messages.push(utils.payloadMessage(messageTypes[missingRequirementMessageType], elementTypes.content, validationMessages.content.missingRequirement));
        moduleItemChecks.messageTypes[missingRequirementMessageType] += 1;

        const missingRequirementTypeMessageType = missingRequirementType ? 'warning' : 'success';
        moduleItemChecks.messages.push(utils.payloadMessage(messageTypes[missingRequirementTypeMessageType], elementTypes.content, validationMessages.content.missingRequirementContribute));
        moduleItemChecks.messageTypes[missingRequirementTypeMessageType] += 1;

        const missingDiscussionTypeMessageType = missingDiscussionType ? 'warning' : 'success';
        moduleItemChecks.messages.push(utils.payloadMessage(messageTypes[missingDiscussionTypeMessageType], elementTypes.content, validationMessages.content.missingDiscussionType));
        moduleItemChecks.messageTypes[missingDiscussionTypeMessageType] += 1;

        const missingGroupCategoryMessageType = missingGroupCategory ? 'warning' : 'success';
        moduleItemChecks.messages.push(utils.payloadMessage(messageTypes[missingGroupCategoryMessageType], elementTypes.content, validationMessages.content.missingGroupCategory));
        moduleItemChecks.messageTypes[missingGroupCategoryMessageType] += 1;        

        return moduleItemChecks;
    },
}

export default async (courseId) => {
    let courseChecks = utils.initPayload();
    const modulesList = await utils.getModulesList(courseId);

    const modulesListHasLength = modulesList.length > 0;
    if (!modulesListHasLength) return sanityValidation.missingModules(courseChecks);
    courseChecks.messages.push(utils.payloadMessage(messageTypes.success, elementTypes.settings, validationMessages.course.noModules));
    courseChecks.messageTypes.success += 1;
    
    const frontpageRes = await utils.apiGet(`/course/${courseId}/frontpage`);
    const frontpageId = frontpageRes?.data?.result?.page_id ?? null;
    let hasFrontpage = false;
    let isFrontpageFirstInList = false;

    const modulesDataRes = await utils.apiGet(`/course/${courseId}/moduletitles`);
    const modulesData = modulesDataRes?.data?.result ?? null;

    const discussionDataRes = await utils.apiGet(`/course/${courseId}/discussion_topics`);
    const discussionData = discussionDataRes?.data?.result ?? null;

    const assignmentsDataRes = await utils.apiGet(`/course/${courseId}/assignments`);
    const assignmentsData = assignmentsDataRes?.data?.result ?? null;
    
    let returnPayload = utils.initPayload();
    let moduleItemIndex = 0;
    let modulesListItemIndex = 0;

    for (const modulesListItem of modulesList) {
        let moduleChecks = utils.initPayload();
        moduleChecks = sanityValidation.isModulesPublished(moduleChecks, modulesListItem, modulesData);

        const moduleItemsDataRes = await utils.apiGet(`/course/${courseId}/modules/${modulesListItem._id}/items`);
        const moduleItemsData = moduleItemsDataRes?.data?.result ?? null;

        for (const moduleItem of modulesListItem?.moduleItems) {
            const moduleItemContent = moduleItem?.content;
            const moduleItemId = moduleItemContent?._id;
            if (!moduleItemId) continue;

            if (moduleItemId == frontpageId) {
                hasFrontpage = true;
                if (modulesListItemIndex == 0 && moduleItemIndex == 0) isFrontpageFirstInList = true;
            }

            let moduleItemChecks = utils.initPayload();
            let moduleItemTitle = "---";
            const currentModuleItemData = moduleItemsData.find((moduleItemData) => {
                if (moduleItemData.title == moduleItemContent?.title) return moduleItemData.published;
                if (moduleItemData.title == moduleItemContent?.name) return moduleItemData.published;
    
                return false;
            });

            moduleItemChecks = sanityValidation.isModuleItemsPublished(moduleItemChecks, currentModuleItemData);

            if (moduleItemContent.description !== undefined) {
                moduleItemTitle = moduleItemContent.name;
                courseChecks.contentTypes.assignments += 1;
                const currentAssignmentData = assignmentsData?.find((assignment) => assignment.name == moduleItemContent.name);

                moduleItemChecks = sanityValidation.validAssignmentSettings(moduleItemChecks, currentModuleItemData, currentAssignmentData);
            } else if (moduleItemContent.message !== undefined) {
                moduleItemTitle = moduleItemContent.title;
                courseChecks.contentTypes.discussions += 1;
                const currentDiscussionData = discussionData?.find((discussion) => discussion.title == moduleItemContent.title);

                moduleItemChecks = sanityValidation.validDiscussionSettings(moduleItemChecks, currentModuleItemData, currentDiscussionData);
            } else {
                moduleItemTitle = moduleItemContent.title;
                courseChecks.contentTypes.pages += 1;
                moduleItemChecks = sanityValidation.validPageSettings(moduleItemChecks, currentModuleItemData);
            }

            moduleItemIndex += 1;
            if (moduleItemChecks?.messages?.length > 0) {
                moduleItemChecks.messages = [utils.payloadMessagesWrapper(moduleItemTitle, "", moduleItemChecks.messages)];
                returnPayload = utils.mergePayloads(returnPayload, moduleItemChecks);
            }
        }

        modulesListItemIndex += 1;
        if (moduleChecks?.messages?.length > 0) {
            moduleChecks.messages = [utils.payloadMessagesWrapper(modulesListItem?.name, "", moduleChecks.messages)];
            returnPayload = utils.mergePayloads(returnPayload, moduleChecks);
        }
    }

    const hasFrontpageType = hasFrontpage ? 'success' : 'error';
    courseChecks.messages.push(utils.payloadMessage(messageTypes[hasFrontpageType], elementTypes.settings, validationMessages.course.noFrontPage));
    courseChecks.messageTypes[hasFrontpageType] += 1;

    const isFrontpageFirstInListType = isFrontpageFirstInList ? 'success' : 'warning';
    courseChecks.messages.push(utils.payloadMessage(messageTypes[isFrontpageFirstInListType], elementTypes.settings, validationMessages.course.frontPageNotFirstItem));
    courseChecks.messageTypes[isFrontpageFirstInListType] += 1;

    courseChecks.messages = [utils.payloadMessagesWrapper("", "", courseChecks.messages)];
    returnPayload = utils.mergePayloads(courseChecks, returnPayload);
    return returnPayload;
}
