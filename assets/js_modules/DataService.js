class DataService {
    constructor(url) {
        this.url = url;
    }

    async fetchData(data) {
        try {
            const response = await fetch(this.url, {
                method: 'POST',
                body: data,
            });

            if (!response.ok) {
                throw new Error(`Failed to fetch data. Status: ${response.status}`);
            }

            const responseData = await response.json();
            return responseData;
        } catch (error) {
            console.error('Error fetching data:', error.message);
            throw error;
        }
    }

    createFormData(data) {
        const formData = new FormData();

        for (const key in data) {
            if (data.hasOwnProperty(key)) {
                formData.append(key, data[key]);
            }
        }

        return formData;
    }
}
export default DataService;
