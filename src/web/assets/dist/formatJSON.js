

function jsonFormatTable() {
    let dataElements = document.querySelectorAll("[data-attr='data']")
    dataElements.forEach(el => {
        if (!el.innerHTML.includes("<pre>")) {
            el.innerHTML = "<pre>" + JSON.stringify(JSON.parse(el.innerHTML), null, '\t') + "</pre>";
        }
        
    })
}

document.querySelector('#format').addEventListener("click", e => {
    e.preventDefault();
    jsonFormatTable();
})





