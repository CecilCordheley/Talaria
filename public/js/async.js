async function useLicence(uuid, cible, type_cible, action, myParam, comment) {
    console.log("param:", myParam); // ← pour vérifier qu'il contient bien des données

    const options = {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            cible: cible,
            type_cible: type_cible,
            action: action,
            params: JSON.stringify(myParam),
            comment: comment
        })
    };

    try {
        console.log("Type de param:", typeof myParam);
        console.log("Instance de Array ?", Array.isArray(myParam));
        console.log("Contenu de param:", myParam);
        console.log("Body JSON.stringify:", JSON.stringify({ params: myParam }));
        const response = await fetch(`async/LicenceFnc_use?uuid=${uuid}`, options);
        const result = await response.text();
        console.dir(result);
    } catch (err) {
        console.error("Erreur fetch:", err);
    }
}

/**
 * envoi une tentative de connexion
 * @param {string} mail Mail de l'agent
 * @param {string} mdp Mot de passe de l'agent
 */
async function connexion(mail, mdp) {
    const options = {
        method: "POST",
        body: JSON.stringify({
            mdp: mdp,
            mail: mail
        })
    };
    fetch("async/connexion", options)
        .then(r => { return r.text })
        .then(result => console.dir(result))
}
async function getActionsLicence(success, failed) {
    fetch("include/exception.json")
        .then(r => { return r.json() })
        .then(result => {
            success.call(this, result);
        })
        .catch(error => { failed.call(this, error) });
}
/**
 * Permet de récupérer tout les agents de la BDD
 * @param {CallableFunction} success 
 * @param {CallableFunction} failed 
 */
async function getAllAgent(success, failed) {
    fetch("async/agent_get")
        .then(r => { return r.json() })
        .then(result => {
            if (result.status.toLowerCase() == "success") {
                success.call(this, result.data);
            } else {
                failed.call(this, result.message);
            }
        })
        .catch(error => {
            console.error(error);
            failed.call(this, error);
        })
}
/**
 * Met à jour le nom, prenom, mail ainsi que le service de l'agent
 * @param {number} id ID de l'agent
 * @param {Object} agent Agent
 */
async function updateAgent(id, agent, success, failed) {
    const options = {
        method: "POST",
        body: JSON.stringify({
            nom: agent["NomAgent"],
            prenom: agent["PrenomAgent"],
            mail: agent["mailAgent"],
            service: agent["service"],
            blocage: agent["blockAgent"]
        })
    };
    fetch("async/agent_update?id=" + id, options)
        .then(r => { return r.text() })
        .then(result => {
            let r = JSON.parse(result);
            if (r.status.toLowerCase() == "success") {
                success.call(this, r.data);
            } else {
                failed.call(r.message);
            }
        })
}
/**
 * 
 * @param {number} ticket Identifiant du ticket
 * @param {number} state Identifiant de l'état
 * @param {string} comment Commentaire associé 
 * @param {FocusEvent} callBack Callback en cas de succès
 */
async function changeStateTicket(ticket, state, comment = "", callBack) {
    fetch("async/ticket_changeState?id=" + ticket + "&state=" + state + "&comment=" + comment)
        .then(r => { return r.text() })
        .then(result => {
            let r = JSON.parse(result);
            if (r.data != undefined) {
                callBack.call(this, r.data);
            }
        })
}
/**
 * met à jour un ticket
 * @param {Array} ticket Ticket à modifier
 * @param {CallableFunction} success Callback en cas de succès
 * @param {CallableFunction} failed Callback en cas d'echec
 */
async function updateTicket(ticket, success, failed) {
    const options = {
        method: "POST",
        body: JSON.stringify({
            ticket: ticket
        })
    };
    fetch("async/ticket_update?id=" + ticket.idTicket, options)
        .then(r => { return r.text() })
        .then(result => {
            let r = JSON.parse(result);
            if (r.status.toLowerCase() == "success") {
                if (success)
                    success.call(this, r.data);
            } else {
                if (failed)
                    failed.call(this, r.message);
                else
                    console.error(r.message);
            }
        })
}
async function createLicence(agent, auto = false, success, failed) {
    fetch("async/licence_add?agent=" + agent + "&auto=" + auto)
        .then(r => { return r.text() })
        .then(result => {
            let r = JSON.parse(result);
            if (r.status == "success") {
                success.call(this, r.data);
            } else {
                failed.call(this, r.message);
            }
        })
        .catch(error => {
            console.error(error);
            failed.call(this, error);
        })
}
/**
 * Rend un service non disponible
 * @param {number} id 
 * @param {CallableFunction} callBack 
 */
async function delService(id, callBack) {
    fetch("async/service_del?id=" + id)
        .then(r => { return r.text() })
        .then(result => {
            let r = JSON.parse(result);
            if (r.status == "success") {
                callBack.call(this, r.data);
            }
        })
}
async function getTicket(orgin = 0, service = "", success, failed) {
    if (orgin != 0) {
        fetch("async/TicketFnc_getTicketFromService?service=" + service)
            .then(r => { return r.json() })
            .then(result => {
                if (result.status.toLowerCase() == "success") {
                    success.call(this, result.data);
                } else {
                    failed.call(this, result.message);
                }
            })
    }
}
/**
 * Créér un type de ticket
 * @param {string} lib Libellé du type de ticket
 * @param {CallableFunction} callBack Callback en cas de succès
 */
async function createTypeTicket(lib, callBack) {
    const options = {
        method: "POST",
        body: JSON.stringify({
            lib: lib
        })
    };
    fetch("async/typeTicket_add", options)
        .then(r => { return r.text() })
        .then(result => {
            let r = JSON.parse(result);
            if (r.data != undefined) {
                callBack.call(this, r.data);
            }
        })
}
/**
 * Créér un service
 * @param {string} nom Nom du service
 * @param {string} desc Description du service
 * @param {Number} defaultManager Identifiant du manager par defaut
 *  * @param {Number} create autorise le service à généré des ticket
 *  * @param {Number} update autorise le service à les mettre à jour
 * @param {CallableFunction} callBack Callback en cas de succès
 */
async function createService(nom,refService, desc = "", defaultManager = null,serviceParent=null, create = "0", update = "0", callBack) {
    const options = {
        method: "POST",
        body: JSON.stringify({
            lib: nom,
            ref:refService,
            desc: desc,
            create: create,
            update: update,
            manager: defaultManager,
            parent:serviceParent
        })
    };
    fetch("async/service_add", options)
        .then(r => { return r.text() })
        .then(result => {
            let r = JSON.parse(result);
            if (r.data != undefined) {
                callBack.call(this, r.data);
            }
        })
}
/**
 * Accède au données d'un ticket
 * @param {number} id identifiant du ticket
 * @param {CallableFunction} callBack Callback en cas de succès
 */
async function seeTicket(id, callBack) {
    fetch("async/ticket_see?id=" + id)
        .then(r => { return r.text() })
        .then(result => {
            let r = JSON.parse(result);
            if (r.data != undefined) {
                callBack.call(this, r.data);
            }
        })

}
/**
 * 
 * @param {string} nom 
 * @param {string*} prenom 
 * @param {string*} ref 
 * @param {string*} mail 
 * @param {number} type 
 * @param {number} service 
 * @param {CallableFunction} callBack 
 */
async function createAgent(nom, prenom, ref, mail, type, service, callBack) {
    const options = {
        method: "POST",
        body: JSON.stringify({
            nom: nom,
            service: service,
            type: type,
            prenom: prenom,
            mail: mail,
            ref: ref
        })
    }
    fetch("async/agent_add", options)
        .then(r => { return r.text() })
        .then(result => {
            let r = JSON.parse(result);
            if (r.data != undefined) {
                callBack.call(this, r.data);
            }
        })
}
/**
 * Récupère les information d'un agent
 * @param {number} id Identifiant de l'agent
 * @param {CallableFunction} success Callback en cas de succès
 * @param {CallableFunction} failed Callback en cas d'échec
 */
async function getAgent(id, success, failed) {
    const response = await fetch("async/agent_get?id=" + id);
    if (!response.ok) {
        throw new Error(`Erreur HTTP: ${response.status}`);
    }
    const result = await response.json();
    if (result.status === "success") {
        console.log("Appel de success callback");
        success?.(result.data);
    } else {
        console.log("Appel de failed callback avec message serveur");
        failed?.(result.message || "Erreur inconnue");
    }
}
/**
 * Attribut un agent en charge du ticket
 * @param {number} idAgent Identifiant de l'agent
 * @param {number} idTicket Identifiant du ticket
 * @param {CallableFunction} success Callback en cas de succès
 * @param {CallableFunction} failed Callback en cas d'echec
 */
async function assignAgent(idAgent, idTicket, success, failed) {
    try {
        const response = await fetch("async/ticket_assign?agent=" + idAgent + "&ticket=" + idTicket);
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }
        const result = await response.json();
        if (result.status === "success") {
            console.log("Appel de success callback");
            success?.(result.data);
        } else {
            console.log("Appel de failed callback avec message serveur");
            failed?.(result.message || "Erreur inconnue");
        }
    } catch (error) {
        console.error("Erreur dans try/catch :", error);
        failed?.(error.message || error.toString());
    }
}
/**
 * 
 * @param {number} id Identifiant du ticket
 * @param {number} service Identifiant du service
 * @param {string} prio Priorité
 * @param {CallableFunction} success Callback en cas de succès
 * @param {CallableFunction} failed Callback en cas d'echec
 */
async function requalif(id, service, prio, success, failed) {
    const response = await fetch("async/ticket_requalif?id=" + id + "&service=" + service + "&prio=" + prio);
    if (!response.ok) {
        throw new Error(`Erreur HTTP: ${response.status}`);
    }

    const result = await response.json();
    if (result.status === "success") {
        console.log("Appel de success callback");
        success?.(result.data);
    } else {
        console.log("Appel de failed callback avec message serveur");
        failed?.(result.message || "Erreur inconnue");
    }

}
async function updateService(serviceObj, success, failed) {
    const options = {
        method: "POST",
        body: JSON.stringify({
            service: JSON.stringify(serviceObj),
        })
    }
    fetch("async/service_update?id=" + serviceObj.idService, options)
        .then(r => { return r.text() })
        .then(result => {
            let r = JSON.parse(result);
            if (r.data != undefined) {
                success.call(this, r.data);
            } else {
                failed.call(this, r.message);
            }
        })
        .catch(message => {
            failed.call(this, message);
        })
}
/**
 * Récupère les information d'un service 
 * @param {number} id ID du service
 * @param {CallableFunction} success Callback en cas de succes
 * @param {CallableFunction} failed Callback en cas d'echec
 */
async function getService(id, success, failed) {
    const response = await fetch("async/service_get?id=" + id);
    if (!response.ok) {
        throw new Error(`Erreur HTTP: ${response.status}`);
    }

    const result = await response.json();
    if (result.status === "success") {
        console.log("Appel de success callback");
        success?.(result.data);
    } else {
        console.log("Appel de failed callback avec message serveur");
        failed?.(result.message || "Erreur inconnue");
    }
}
/**
 * Récupère l'ensemble des services
 * @param {CallableFunction} success Callback en cas de succès
 * @param {CallableFunction} failed Callback en cas d'echec
 */
async function getServices(success, failed) {
    console.log("Début getServices");
    try {
        const response = await fetch("async/service_get");
        console.log("Réponse fetch obtenue");

        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }

        const result = await response.json();
        console.log("JSON parsé :", result);

        if (result.status === "success") {
            console.log("Appel de success callback");
            success?.(result.data);
        } else {
            console.log("Appel de failed callback avec message serveur");
            failed?.(result.message || "Erreur inconnue");
        }
    } catch (error) {
        console.error("Erreur dans try/catch :", error);
        failed?.(error.message || error.toString());
    }
}
/**
 * 
 * @param {string} objet 
 * @param {number} service 
 * @param {number} type 
 * @param {string} content 
 * @param {Array} datas 
 * @param {CallableFunction} success Callback en cas de succès
 * @param {CallableFunction} failed Callback en cas d'echec
 */
async function addTicket(objet, service, type, content, datas, success, failed) {
    const options = {
        method: "POST",
        body: JSON.stringify({
            objet: objet,
            service: service,
            type: type,
            content: content,
            data: datas
        })
    }
    fetch("async/ticket_add", options)
        .then(r => { return r.text() })
        .then(result => {
            let r = JSON.parse(result);
            if (r.data != undefined) {
                success.call(this, r.data);
            } else {
                failed.call(this, r.message);
            }
        })
        .error(message => {
            document.querySelector("#newTicket .result").innerText = message;
        })
}