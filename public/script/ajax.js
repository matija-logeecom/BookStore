export function getQueryParameter(name) {
    const urlParams = new URLSearchParams(window.location.search)
    return urlParams.get(name)
}

async function fetchData(url) {
    try {
        const response = await fetch(url)
        if (!response.ok) throw new Error("Error fetching data.")
        return await response.json()
    } catch (err) {
        console.error(err)
        throw err
    }
}

export async function sendData(url, method = "POST", data = {}) {
    try {
        const response = await fetch(url, {
            method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        if (!response.ok) throw new Error("Error sending data.")
        return await response.json()
    } catch (err) {
        console.error(err)
        throw err
    }
}

export async function fetchAuthorBooks(authorId) {
    return fetchData(`placeholder/${authorId}`)
}