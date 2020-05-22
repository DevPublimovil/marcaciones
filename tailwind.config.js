module.exports = {
  purge: [],
  theme: {
    customForms: theme => ({
      default: {
        input: {
          borderColor: theme('colors.gray.400'),
          borderRadius: theme('borderRadius.lg'),
          backgroundColor: theme('colors.gray.100'),
          '&:focus': {
            backgroundColor: theme('colors.white'),
          }
        },
        select: {
          borderRadius: theme('borderRadius.lg'),
          boxShadow: theme('boxShadow.default'),
        },
        checkbox: {
          width: theme('spacing.6'),
          height: theme('spacing.6'),
        },
      },
    }),
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
    require('@tailwindcss/custom-forms'),
    require('tailwindcss-plugins/pagination')
  ],
}
