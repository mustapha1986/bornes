const API_URL = 'https://api.example.com';

async function fetchData() {
    const response = await fetch(API_URL);
    return response.json();
}

export { fetchData };