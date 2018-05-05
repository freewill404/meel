<template>
    <div>
        <input v-on:input="debounceInput" type="text" name="when" class="field" :class="{ 'border-red': ! valid }" placeholder="now">
        <small class="block h-4" :class="{ 'text-red': ! valid }">{{ valid ? humanInterpretation : 'Huh?' }}</small>
    </div>
</template>

<script>
    export default {

        props: [

        ],

        data: () => ({
            exampleData: [],
            humanInterpretation: '',
            valid: true,
        }),

        mounted() {

        },

        methods: {
            
            debounceInput: _.debounce(function (e) {
                    axios.post('/api/v1/human-when-interpretation', {"when": e.target.value}).then(response => {
                        this.valid = response.data.valid;

                        this.humanInterpretation = response.data.humanInterpretation;
                    });
                }, 200)

        }

    }
</script>