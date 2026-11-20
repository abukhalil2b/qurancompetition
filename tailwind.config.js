module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
  ],
  darkMode: "class",
  theme: {
    container: {
      center: true,
    },
    extend: {
      colors: {
        brand: {
          teal: {
            50: "#F0FAF9",
            100: "#D6F0ED",
            200: "#AEE0DA",
            300: "#78C7BE",
            400: "#42A89D",
            500: "#1E8A7F",
            600: "#146E66",
            700: "#0F5953",
            800: "#0B4440",
            900: "#07312E",
            950: "#041F1D",
          },
          gold: {
            50: "#FBF8F0",
            100: "#F4ECD8",
            200: "#E8D7B0",
            300: "#D8BC82",
            400: "#C9A66B",
            500: "#B68D4C",
            600: "#9A7237",
            700: "#7D5929",
            800: "#634622",
            900: "#4F381C",
          },
          sand: {
            50: "#FAF8F4",
            100: "#F4EFE6",
            200: "#E8DEC9",
            300: "#D8C7A8",
            400: "#C3AC84",
          },
        },
        primary: {
          DEFAULT: "#0F5953",
          light: "#1E8A7F",
          "dark-light": "rgba(15,89,83,.15)",
        },
        secondary: {
          DEFAULT: "#C9A66B",
          light: "#F4ECD8",
          "dark-light": "rgba(201,166,107,.15)",
        },
        success: {
          DEFAULT: "#00ab55",
          light: "#ddf5f0",
          "dark-light": "rgba(0,171,85,.15)",
        },
        danger: {
          DEFAULT: "#e7515a",
          light: "#fff5f5",
          "dark-light": "rgba(231,81,90,.15)",
        },
        warning: {
          DEFAULT: "#e2a03f",
          light: "#fff9ed",
          "dark-light": "rgba(226,160,63,.15)",
        },
        info: {
          DEFAULT: "#2196f3",
          light: "#e7f7ff",
          "dark-light": "rgba(33,150,243,.15)",
        },
        dark: {
          DEFAULT: "#3b3f5c",
          light: "#eaeaec",
          "dark-light": "rgba(59,63,92,.15)",
        },
        black: {
          DEFAULT: "#0e1726",
          light: "#e3e4eb",
          "dark-light": "rgba(14,23,38,.15)",
        },
        white: {
          DEFAULT: "#ffffff",
          light: "#e0e6ed",
          dark: "#888ea8",
        },
      },
      fontFamily: {
        nunito: ["Nunito", "sans-serif"],
        tajawal: ["Tajawal", "Helvetica", "sans-serif"],
        amiri: ["Amiri", "serif"],
      },
      spacing: {
        4.5: "18px",
      },
      boxShadow: {
        "3xl":
          "0 2px 2px rgb(224 230 237 / 46%), 1px 6px 7px rgb(224 230 237 / 46%)",
      },
      typography: {
        DEFAULT: {
          css: {
            h1: { fontSize: "40px" },
            h2: { fontSize: "32px" },
            h3: { fontSize: "28px" },
            h4: { fontSize: "24px" },
            h5: { fontSize: "20px" },
            h6: { fontSize: "16px" },
          },
        },
      },
    },
  },
  plugins: [
    require("@tailwindcss/forms")({
      strategy: "base",
    }),
    require("@tailwindcss/typography"),
  ],
};
