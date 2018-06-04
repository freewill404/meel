<template>
    <span @click="requestFormat()" class="text-xs text-grey-dark" :class="{ 'cursor-pointer': ! recentlyRequested }">
        {{ recentlyRequested ? 'Thank you, i\'ll see what i can do' : 'Request this format' }}
    </span>
</template>

<script>
    export default {

        props: {
            when: String,
        },

        data: () => ({
            recentlyRequested: false,
        }),

        methods: {
            requestFormat: function() {
                if (this.recentlyRequested) {
                    return;
                }

                this.recentlyRequested = true;

                axios.post('/api/v1/request-when-format', { 'when': this.when });
            }

        }
    }
</script>