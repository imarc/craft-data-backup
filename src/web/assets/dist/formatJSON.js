
function waitUntilSelectorExist(selector, interval = 100, timeout = 20000) {
    let intervalId;
    let elapsed = 0;

    let promise = new Promise(function(resolve, reject) {
        intervalId = setInterval(() => {
            let els = document.querySelectorAll(selector)
            if (els.length > 0) {
                clearInterval(intervalId);
                resolve(els);
            }
            elapsed += interval;
            if (elapsed > timeout) {
                clearInterval(intervalId);
                reject(`The selector ${selector} did not enter within the ${timeout}ms frame!`);
            }
        }, interval);
    });

    return promise;
}

(async() => {
    try {
        await waitUntilSelectorExist("[data-attr='data']");
        let dataElements = document.querySelectorAll("[data-attr='data']")
        dataElements.forEach(el => {
            el.innerHTML = "<pre>" + JSON.stringify(JSON.parse(el.innerHTML), null, '\t') + "</pre>";
        })
    } catch (error) {
        console.warn(error); // timeout!
    }
})();