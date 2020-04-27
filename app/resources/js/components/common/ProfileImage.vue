<template>
    <span :style="styles.wrapper">
        <span :style="styles.image"></span>
    </span>
</template>
<script>
export default {
    name: 'ProfileImage',

    props: {
        src: {
            type    : String,
            required: false
        },
        size: {
            type   : Number,
            default: 30
        }
    },

    data: () => ({
        styles: {
            wrapper: {
                'border-radius': '999999px',
                'border-width' : '2px',
                'border-style' : 'solid',
                'border-color' : 'rgba(255, 255, 255, .5)',
                display        : 'inline-block'
            },

            image: {
                'background-image'   : '',
                'background-position': '',
                'background-size'    : '',
                'border-radius'      : '',
                border               : '',
                'vertical-align'     : '',
                display              : '',
                height               : '',
                width                : '',
            }
        }
    }),

    mounted () {
        this.styles.image['background-image']    = this.src ? 'url(' + this.src + ')' : 'url(/img/placeholders/user.png)';
        this.styles.image['display']             = 'inline-block';
        this.styles.image['background-position'] = 'center';
        this.styles.image['background-size']     = 'cover';
        this.styles.image['vertical-align']      = 'middle';
        this.styles.image['height']              = this.size + 'px';
        this.styles.image['width']               = this.size + 'px';
        this.styles.image['border-radius']       = '50%';
    }
};
</script>
