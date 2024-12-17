/// global composable

export const sendRequest = async ({ method = "GET", url, data = null }) => {
  try {
    const headers = {
      "Content-Type": "application/json",
    };

    const token = localStorage.getItem("token");

    if (token) {
      headers["Authorization"] = `Bearer ${token}`;
    }

    const response = await $fetch(`http://localhost:8000/api${url}`, {
      method,
      body: JSON.stringify(data),
      headers,
    });

    return {
      data: response.response,
      error: null,
    };
  } catch (error) {
    console.log(error);
    return {
      data: null,
      error: error.response?.data?.message || "Something went wrong",
    };
  }
};
