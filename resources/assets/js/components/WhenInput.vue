<template>
    <div>
        <input v-model="when"
               v-on:input="debounceInput"
               name="when"
               class="field"
               :class="{ 'border-red': ! valid }"
               placeholder="now"
               :pattern="valid ? '.*' : '^$'"
        >

        <div class="flex items-center justify-between h-4">
            <small :class="{ 'text-red': ! valid }">
                {{ valid ? humanInterpretation : 'Huh?' }}
            </small>

            <request-format v-if="! valid" :when="requestFormat"></request-format>
        </div>
    </div>
</template>

<script>
    export default {

        data: () => ({
            humanInterpretation: '',
            valid: true,
            requestFormat: '', // passing "when" to the RequestFormat component was causing errors
        }),

        methods: {
            debounceInput: _.debounce(function () {
                this.requestFormat = this.when;

                axios.post('/api/v1/human-when-interpretation', { 'when': this.when }).then(response => {
                    this.valid = response.data.valid;

                    this.humanInterpretation = response.data.humanInterpretation;
                });
            }, 200)

        }
    }
</script>