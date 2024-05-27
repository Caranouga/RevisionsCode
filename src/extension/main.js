document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('submit').addEventListener('click', addServer);
    document.getElementById('delete').addEventListener('click', deleteServer);
    document.getElementById('send').addEventListener('click', sendRequest);

    loadURLS();
});

function addServer(){
    const input = document.getElementById('url');
    const select = document.getElementById('server');
    const url = input.value;

    if(!url) return;
    if(!isValidUrl(url)) return;
    if(url.endsWith('/')) url = url.slice(0, -1);

    addUrl(url, select);
    input.value = '';
    select.selectedIndex = select.options.length - 1;
    saveURLS();
}

function deleteServer(){
    const select = document.getElementById('server');
    const index = select.selectedIndex;

    if(index === 0) return;

    select.remove(index);
    select.selectedIndex = 0;
    saveURLS();
}

function sendRequest(){
    const select = document.getElementById('server');
    const api_key = document.getElementById('key').value;

    if(select.options.length === 0) return;
    let url = select.options[select.selectedIndex].value;
    url += '/api.php';

    const date = new Date().toISOString().split('T')[0];
    const time = new Date().toTimeString().split(' ')[0].slice(0, -3);

    // Check if the current page is https://www.passetoncode.fr/nostests_resultats.php#theapp
    if (!window.location.href === 'https://www.passetoncode.fr/nostests_resultats.php#theapp') {
        alert('Veuillez vous rendre sur la page de résultats de votre test de code pour envoyer les données.');
        return;
    }

    // Get all the scripts on the page (not on the extension popup)
    chrome.tabs.query({ active: true, currentWindow: true }, function(tabs) {
        chrome.tabs.sendMessage(tabs[0].id, { action: "getScripts" }, function(response) {
            if(response && response.scripts){
                // let script = response.scripts[13];

                // script = script.split('series:')[1];
                // script = script.split('});')[0];

                // let firstData = script.split('data: [')[1];
                // firstData = firstData.split(']')[0];
                // firstData = firstData.split(',');

                // let secondData = script.split('data: [')[2];
                // secondData = secondData.split(']')[0];
                // secondData = secondData.split(',');

                let script = response.scripts[13].split('series:')[1].split('});')[0];
                let firstData = script.split('data: [')[1].split(']')[0].split(',');
                let secondData = script.split('data: [')[2].split(']')[0].split(',');

                for (let i = 0; i < firstData.length; i++) {
                    firstData[i] = parseInt(firstData[i]);
                    secondData[i] = parseInt(secondData[i]);
                }

                let totals = firstData.map((value, index) => value + secondData[index]);
                let erreurs = secondData;


                const datas = {
                    signalisation: {
                        total: totals[0],
                        erreurs: erreurs[0]
                    },
                    stationnement: {
                        total: totals[1],
                        erreurs: erreurs[1]
                    },
                    feux: {
                        total: totals[2],
                        erreurs: erreurs[2]
                    },
                    vehicule: {
                        total: totals[3],
                        erreurs: erreurs[3]
                    },
                    depassement: {
                        total: totals[4],
                        erreurs: erreurs[4]
                    },
                    orientation: {
                        total: totals[5],
                        erreurs: erreurs[5]
                    },
                    priorites: {
                        total: totals[6],
                        erreurs: erreurs[6]
                    },
                    conducteur: {
                        total: totals[7],
                        erreurs: erreurs[7]
                    }
                }
            
                // POST request
                const data = {
                    date: date,
                    time: time,
                    datas: datas,
                    api_key: api_key
                }
            
                const options = {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data),
                }
            
                fetch(url, options).then(response => {
                    if(response.ok){
                        // Check if the response is a JSON
                        response.json().then(json => {
                            // The json is like { success: true, message: 'Data received' }
                            if(json.success){
                                alert('Les données ont bien été envoyées');
                            }else{
                                alert('Une erreur est survenue lors de l\'envoi des données\n' + json.message);
                            }
                        });
                    }else{
                        alert('Une erreur est survenue lors de l\'envoi des données');
                    }
                });
            }
        });
    });
}

function isValidUrl(string) {
    try {
        new URL(string);
        return true;
    } catch (err) {
        return false;
    }
}

function addUrl(url, select){
    const option = document.createElement('option');
    option.value = url;
    option.text = url;
    select.appendChild(option);
}

function saveURLS(){
    const select = document.getElementById('server');
    const urls = [];
    for (let i = 1; i < select.options.length; i++) {
        urls.push(select.options[i].value);
    }
    localStorage.setItem('serverURLs', JSON.stringify(urls));
}

function loadURLS(){
    const select = document.getElementById('server');
    const urls = JSON.parse(localStorage.getItem('serverURLs')) || [];
    urls.forEach(url => {
        addUrl(url, select);
    });
}