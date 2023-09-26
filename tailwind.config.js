import colors from "tailwindcss/colors";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";

export default {
    content: ["./resources/**/*.blade.php", "./vendor/filament/**/*.blade.php"],
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                danger: colors.rose,
                primary: {
                    50: "#F0FFFC",
                    100: "#D6FFF8",
                    200: "#803333",
                    300: "#803333",
                    400: "#803333",
                    500: "#803333",
                    600: "#803333",
                    700: "#803333",
                    800: "#803333",
                    900: "#803333",
                },
                success: colors.green,
                warning: colors.yellow,
            },
        },
    },
    plugins: [forms, typography],
};
