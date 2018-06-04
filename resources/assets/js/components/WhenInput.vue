<template>
    <div>
        <input v-model="when" v-on:input="debounceInput" name="when" class="field" :class="{ 'border-red': ! valid }" placeholder="now">

        <div class="flex items-center justify-between h-4">
            <small :class="{ 'text-red': ! valid }">
                {{ valid ? humanInterpretation : 'Huh?' }}
            </small>

            <request-format v-if="! valid" :when="when"></request-format>
        </div>
    </div>
</template>

<script>
    export default {

        data: () => ({
            humanInterpretation: '',
            valid: true,
        }),

        methods: {
            debounceInput: _.debounce(function (e) {
                axios.post('/api/v1/human-when-interpretation', { 'when': this.when }).then(response => {
                    this.valid = response.data.valid;

                    this.humanInterpretation = response.data.humanInterpretation;
                });
            }, 200)

        }
    }
</script>