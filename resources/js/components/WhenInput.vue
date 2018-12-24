<template>
    <div>
        <input v-model="when"
               v-on:input="debounceInput"
               name="when"
               class="field mb-1"
               maxlength="255"
               :class="{ 'border-red': ! valid }"
               :placeholder="defaultWhen"
               :pattern="valid ? '.*' : '^$'"
        >

        <div class="text-xs h-4" :class="{ 'text-red': ! valid }">
            {{ valid ? humanInterpretation : (humanInterpretation ? humanInterpretation : 'Huh?') }}
        </div>
    </div>
</template>

<script>
    export default {

        props: {
            defaultWhen: String,
            initialWhen: String,
            feature: String,
        },

        data: () => ({
            when: '',
            humanInterpretation: '',
            valid: true,
            sessionIdentifier: Math.floor(Math.random() * 1e16).toString(),
        }),

        mounted: function() {
            this.when = this.initialWhen;

            if (this.when) {
                this.validateWhen();
            }
        },

        methods: {
            debounceInput: _.debounce(function () {
                this.validateWhen();
            }, 200),

            validateWhen: function () {
                axios.post(`/api/v1/human-when-interpretation/${this.feature}`, {
                    'written_input': this.when,
                    'session_id': this.sessionIdentifier,
                    'source': this.feature,
                }).then(response => {
                    this.valid = response.data.valid;

                    this.humanInterpretation = response.data.humanInterpretation;
                });
            },

        }
    }
</script>