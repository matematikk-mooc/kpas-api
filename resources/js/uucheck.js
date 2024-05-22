import api from "./api";
// export default (function () {

async function Get (last_part_of_url){
    return await api.get(location.origin + '/api' +last_part_of_url, {
        params: {
            cookie: window.cookie
        }});
}




function checkHTML(html) {
    let content = document.createElement('div');
    content.innerHTML = html;
    let output = [];

    // Image check
    let imgTags = content.querySelectorAll('img');
    for (const img of imgTags) {
        if (img.getAttribute('role') === 'presentation') continue;
        if (img.getAttribute('data-decorative')) {
            output.push({ type: 'warning', message: img.alt, description: 'Bilde er merket som dekorativt på den gamle ustandardiserte måten.' });
        } else if (!['.jpg', '.jpeg', '.gif', '.png', '.svg'].includes(img.alt.substring(img.alt.lastIndexOf('.')))) {
            output.push({ type: 'error', message: img.alt, description: 'Bilde må markeres som dekorativt eller få alternativ tekst som ikke er filnavnet.' });
        }
    }

    // Table check
    let tables = content.querySelectorAll('table');
    for (const table of tables) {
        if (!table.querySelector('caption')) {
            output.push({ type: 'error', message: 'Tabell', description: 'Tabell mangler overskrift.' });
        }
        if (!table.querySelector('th')) {
            output.push({ type: 'error', message: 'Tabell', description: 'Tabell mangler titler på rader og/eller kolonner.' });
        }
    }

    // Header check
    let headers = content.querySelectorAll("h2, h3, h4, h5, h6");
    let startLevel = 1;
    for (const header of headers) {
        let newLevel = Number(header.tagName.substring(1));
        if (newLevel > startLevel + 1) {
            output.push({ type: 'error', message: 'Header', description: 'Feil i overskriftshierarkiet.' });
            break;
        }
        startLevel = newLevel;
    }

    // Style check
    if (html.includes('style=')) {
        output.push({ type: 'warning', message: 'HTML', description: 'Bruker style - sjekk om det brukes for å lage overskrift, ødelegger kontrast eller fører til andre problemer.' });
    }

    return output;
}

let errorCount = 0;
let warningCount = 0;
let dataArray = [];

function assignmentcheck(item) {
    let log = checkHTML(item.description);
    if (log.length > 0) {
        let itemData = {
            type: 'assignment',
            itemId: item._id,
            itemName: item.name,
            errors: [],
            warnings: []
        };
        log.forEach((error) => {
            if (error.type === 'error') {
                errorCount++;
                itemData.errors.push(error);
            } else if (error.type === 'warning') {
                warningCount++;
                itemData.warnings.push(error);
            }
        });
        dataArray.push(itemData);
    }
}

function discussioncheck(item) {
    let log = checkHTML(item.message);
    if (log.length > 0) {
        let itemData = {
            type: 'discussion',
            itemId: item._id,
            itemName: item.title,
            errors: [],
            warnings: []
        };
        log.forEach((error) => {
            if (error.type === 'error') {
                errorCount++;
                itemData.errors.push(error);
            } else if (error.type === 'warning') {
                warningCount++;
                itemData.warnings.push(error);
            }
        });
        dataArray.push(itemData);
    }
}

async function pagecheck(item, courseid) {
    if (!item._id) item._id = item.page_id;
    let response = await api.get(`api/course/${courseid}/coursepage/${item._id}`, {
        params: {
            cookie: window.cookie
        }});
    let pageData = response.reult
    if (!pageData) return;
    let log = checkHTML(pageData);
    if (log.length > 0) {
        let itemData = {
            type: 'page',
            itemId: item._id,
            itemName: item.title,
            errors: [],
            warnings: []
        };
        log.forEach((error) => {
            if (error.type === 'error') {
                errorCount++;
                itemData.errors.push(error);
            } else if (error.type === 'warning') {
                warningCount++;
                itemData.warnings.push(error);
            }
        });
        dataArray.push(itemData);
    }
}

async function UUCheck(content, courseid){
    let modules = content.data.course.modulesConnection.nodes
    if (modules.length){
        await Get(`/course/${courseid}/coursedata`)
        .then( coursedata => {
            if (coursedata.default_view == 'wiki'){
                let pages = Get(`/course/${courseid}/coursepages`)
                for (const page of pages){
                    if (page.front_page){
                        pagecheck(page, courseid)
                        break
                    }
                }
            }
            for (const module of modules){
                for (const item of module.moduleItems){
                    if (!item.content._id) continue
                    if (item.content.description) { assignmentcheck(item.content); continue}
                    if (item.content.message) { discussioncheck(item.content); continue}
                    pagecheck(item.content, courseid)
                }
            }
        })
    }else{
        await Get(`/course/${courseid}/coursepages`)
        .then(pages => {
            if (pages.length){
                for (const page of pages){
                    pagecheck(page, courseid)
                }
            }
        })
    }
    dataArray.push({ type: 'summary', errors: errorCount, warnings: warningCount });
    return dataArray;
}
export default UUCheck;
// return {
//     UUCheck: async function (content, courseid){
//         return await UUsjekk(content, courseid);

//     }
// }
// })();
