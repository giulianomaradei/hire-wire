/// global composable

export const sendRequest = async ({ method = "GET", url, data = null }) => {
  try {
    const headers = {
      "Content-Type": "application/json",
      Accept: "application/json",
    };

    const token = localStorage.getItem("token");

    if (token) {
      headers["Authorization"] = `Bearer ${token}`;
    }

    const payload = {
      method,
      headers,
    };

    if (data) {
      payload.body = JSON.stringify(data);
    }

    const response = await $fetch(`http://localhost:8000/api${url}`, payload);

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
