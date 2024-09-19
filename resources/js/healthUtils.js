import Player from '@vimeo/player'
import api from './api';

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
            pages: (payload1.contentTypes?.pages || 0) + (payload2.contentTypes?.pages || 0),
            assignments: (payload1.contentTypes?.assignments || 0) + (payload2.contentTypes?.assignments || 0),
            discussions: (payload1.contentTypes?.discussions || 0) + (payload2.contentTypes?.discussions || 0)
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
            messages: [...payload1?.messages ?? [], ...payload2?.messages ?? []]
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
    },
    getModulesList: async (courseId) => {
        const modulesListDataRes = await utils.apiGet(`/course/${courseId}/moduleslist`);
        const modulesListData = modulesListDataRes?.data?.result ?? [];
        if (!modulesListData) [];

        return modulesListData;
    },
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

export const captionValidation = {
    vimeo: async (returnPayload, title, htmlString, multilang = "NONE") => {
        if(!htmlString || htmlString == null || htmlString == "") return returnPayload;

        const htmlDocumentParser = new DOMParser();
        const htmlDocument = htmlDocumentParser.parseFromString(htmlString, 'text/html');

        const vimeoIframes = htmlDocument.querySelectorAll('iframe[src*="vimeo.com"]');
        const renderIframesContainer = document.getElementById('render-iframes-hidden');

        const messageWrapper = utils.payloadMessagesWrapper(title, "", []);
        for (const iframeItem of vimeoIframes) {
            try {
                renderIframesContainer.appendChild(iframeItem);
                const iframePlayer = new Player(iframeItem);
                
                await new Promise(resolve => {
                    iframePlayer.getTextTracks().then(tracks => {
                        let missingCaptions = false;
                        const languagesState = {
                            nb: false,
                            nn: false,
                            se: false,
                        }

                        for (const track of tracks) {
                            if (track.language?.toLowerCase() == "nb" || track.language?.toLowerCase() == "no") languagesState.nb = true;
                            if (track.language?.toLowerCase() == "nn") languagesState.nn = true;
                            if (track.language?.toLowerCase() == "se") languagesState.se = true;
                        }

                        if (languagesState.nb != true) {
                            messageWrapper.messages.push(utils.payloadMessage(messageTypes.error, elementTypes.captions, { _key: "message.captions.vimeo", nb: `Mangler bokmål tekst - ${iframeItem.src}` }));
                            missingCaptions = true;
                        }

                        if (multilang == 'NN' || multilang == 'ALL') {
                            if (languagesState.nn != true) {
                                messageWrapper.messages.push(utils.payloadMessage(messageTypes.error, elementTypes.captions, { _key: "message.captions.vimeo", nb: `Mangler nynorsk tekst - ${iframeItem.src}` }));
                                missingCaptions = true;
                            }
                        }

                        if (multilang == 'SE' || multilang == 'ALL') {
                            if (languagesState.se != true) {
                                messageWrapper.messages.push(utils.payloadMessage(messageTypes.error, elementTypes.captions, { _key: "message.captions.vimeo", nb: `Mangler samisk tekst - ${iframeItem.src}` }));
                                missingCaptions = true;
                            }
                        }

                        if (missingCaptions) returnPayload.messageTypes.error += 1;
                        else returnPayload.messageTypes.success += 1;
                    }).catch(error => {
                        messageWrapper.messages.push(utils.payloadMessage(messageTypes.warning, elementTypes.captions, { _key: "message.captions.vimeo", nb: `${error.message} - ${iframeItem.src}` }));
                        returnPayload.messageTypes.warning += 1;
                    }).finally(() => {
                        renderIframesContainer.removeChild(iframeItem);
                        resolve();
                    });
                });
            } catch (error) {
                messageWrapper.messages.push(utils.payloadMessage(messageTypes.warning, elementTypes.captions, { _key: "message.captions.vimeo", nb: `${error.message} - ${iframeItem.src}` }));
                returnPayload.messageTypes.warning += 1;
            }
        }

        if(messageWrapper.messages.length > 0) returnPayload.messages.push(messageWrapper);
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
    settings: { _key: "settings", nb: "Innstillinger" },
    links: { _key: "links", nb: "Lenker" },
    captions: { _key: "captions", nb: "Undertekster" },

    heading: { _key: "heading", nb: "Overskrift" },
    image: { _key: "image", nb: "Bilde" },
    table: { _key: "table", nb: "Tabell" },
    style: { _key: "style", nb: "Stil" }
};

export const validationMessages = {
    course: {
        noModules: { _key: "message.course.noModules", nb: "Moduler er ikke satt opp." },
        noFrontPage: { _key: "message.course.noFrontPage", nb: "Forsiden er ikke satt opp." },
        frontPageNotFirstModule: { _key: "message.course.frontPageNotFirstModule", nb: "Forsiden er ikke i første modul." },
        frontPageNotFirstItem: { _key: "message.course.frontPageNotFirstItem", nb: "Forsiden er ikke første element i første modul." }
    },
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
