import { utils, captionValidation } from "./healthUtils";

export default async (courseId) => {
    let returnPayload = utils.initPayload();
    
    const modulesList = await utils.getModulesList(courseId);
    const modulesListHasLength = modulesList.length > 0;
    if (!modulesListHasLength) return returnPayload;

    const courseSettingsRes = await utils.apiGet(`/course/${courseId}/settings`);
    const courseSettings = courseSettingsRes.data?.result;
    const courseMultilang = courseSettings?.multilang ?? "NONE";

    for (const moduleItem of modulesList) {
        const contentItems = moduleItem?.moduleItems ?? [];

        for (const contentItem of contentItems) {
            const itemObject = contentItem?.content;
            if (itemObject != null) {
                const itemId = itemObject?._id;
                let itemContent = null;
                let itemTitle = null;

                if (itemObject?.message) {
                    returnPayload.contentTypes.discussions += 1;
                    itemContent = itemObject?.message;
                    itemTitle = itemObject?.title;
                } else if (itemObject?.description) {
                    returnPayload.contentTypes.assignments += 1;
                    itemContent = itemObject?.description;
                    itemTitle = itemObject?.name;
                }

                if (itemContent == null && itemId != null) {
                    try {
                        const itemPageRes = await utils.apiGet(`/course/${courseId}/coursepage/${itemId}`);
                        if (itemPageRes.status == 200) {
                            returnPayload.contentTypes.pages += 1;
                            itemContent = itemPageRes.data?.result?.body;
                            itemTitle = itemObject?.title;
                        }
                    } catch (error) {
                        console.error(error);   
                    }
                }

                returnPayload = await captionValidation.vimeo(returnPayload, itemTitle, itemContent, courseMultilang);
            }
        }
    }

    return returnPayload;
}
