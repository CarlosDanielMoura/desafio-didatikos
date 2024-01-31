import Cookies from "js-cookie";

export default {
  auth(to, from, next) {
    const token = Cookies.get("my_token");

    if (!token) {
      next("/login");
    }
    next();
  },
};
