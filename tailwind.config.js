module.exports = {
  purge: [],
  theme: {
    extend: {
      colors: {
        primary: '#335eea',
        primaryhover: '#0C81FE',
        primaryshadow: '#2924D4',
        primaryred: '#FF5A5F',
        primarygreen: '#42ba96',
      },
    },
  },
  variants: {},
  plugins: [
    require('@tailwindcss/custom-forms')
  ],
}
