<template>
    <div class="py-1 mb-3">
        <input ref="whatInput"
               v-model="what"
               @blur="save()"
               @keyup.enter="save()"
               maxlength="255"
               class="w-full"
               :class="{ 'bg-grey-lightest': ! isEditing, 'field': isEditing }"
               :disabled="! isEditing"
        >

        <div class="text-xs flex items-center mt-1">
            <span>{{ nextOccurrence ? nextOccurrence : lastSentAt | secondless }}</span>

            <span v-if="isRecurring" class="inline-block ml-2 w-3 h-3">
                <svg viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1664 896q0 156-61 298t-164 245-245 164-298 61q-172 0-327-72.5t-264-204.5q-7-10-6.5-22.5t8.5-20.5l137-138q10-9 25-9 16 2 23 12 73 95 179 147t225 52q104 0 198.5-40.5t163.5-109.5 109.5-163.5 40.5-198.5-40.5-198.5-109.5-163.5-163.5-109.5-198.5-40.5q-98 0-188 35.5t-160 101.5l137 138q31 30 14 69-17 40-59 40h-448q-26 0-45-19t-19-45v-448q0-42 40-59 39-17 69 14l130 129q107-101 244.5-156.5t284.5-55.5q156 0 298 61t245 164 164 245 61 298z"></path></svg>
            </span>

            <span v-if="isRecurring" class="ml-1">{{ when }}</span>

            <span v-if="timesSent" class="inline-block ml-4 w-3 h-3">
                <svg viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1664 1504v-768q-32 36-69 66-268 206-426 338-51 43-83 67t-86.5 48.5-102.5 24.5h-2q-48 0-102.5-24.5t-86.5-48.5-83-67q-158-132-426-338-37-30-69-66v768q0 13 9.5 22.5t22.5 9.5h1472q13 0 22.5-9.5t9.5-22.5zm0-1051v-24.5l-.5-13-3-12.5-5.5-9-9-7.5-14-2.5h-1472q-13 0-22.5 9.5t-9.5 22.5q0 168 147 284 193 152 401 317 6 5 35 29.5t46 37.5 44.5 31.5 50.5 27.5 43 9h2q20 0 43-9t50.5-27.5 44.5-31.5 46-37.5 35-29.5q208-165 401-317 54-43 100.5-115.5t46.5-131.5zm128-37v1088q0 66-47 113t-113 47h-1472q-66 0-113-47t-47-113v-1088q0-66 47-113t113-47h1472q66 0 113 47t47 113z"></path></svg>
            </span>

            <span v-if="timesSent" class="ml-1">{{ timesSent }}x</span>

            <span v-if="nextOccurrence" class="ml-4 w-3 h-3 cursor-pointer" @click="edit()" title="Edit">
                <svg viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M491 1536l91-91-235-235-91 91v107h128v128h107zm523-928q0-22-22-22-10 0-17 7l-542 542q-7 7-7 17 0 22 22 22 10 0 17-7l542-542q7-7 7-17zm-54-192l416 416-832 832h-416v-416zm683 96q0 53-37 90l-166 166-416-416 166-165q36-38 90-38 53 0 91 38l235 234q37 39 37 91z"></path></svg>
            </span>

            <span class="ml-2 cursor-pointer" :class="{ 'text-red-light': deleteClickedOnce }" @click="remove()" title="Delete">
                <svg class="fill-current w-3 h-3" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M704 1376v-704q0-14-9-23t-23-9h-64q-14 0-23 9t-9 23v704q0 14 9 23t23 9h64q14 0 23-9t9-23zm256 0v-704q0-14-9-23t-23-9h-64q-14 0-23 9t-9 23v704q0 14 9 23t23 9h64q14 0 23-9t9-23zm256 0v-704q0-14-9-23t-23-9h-64q-14 0-23 9t-9 23v704q0 14 9 23t23 9h64q14 0 23-9t9-23zm-544-992h448l-48-117q-7-9-17-11h-317q-10 2-17 11zm928 32v64q0 14-9 23t-23 9h-96v948q0 83-47 143.5t-113 60.5h-832q-66 0-113-58.5t-47-141.5v-952h-96q-14 0-23-9t-9-23v-64q0-14 9-23t23-9h309l70-167q15-37 54-63t79-26h320q40 0 79 26t54 63l70 167h309q14 0 23 9t9 23z"></path></svg>
                <span v-if="deleteClickedOnce" class="ml-1">Are you sure?</span>
            </span>

        </div>
    </div>
</template>

<script>
    export default {

        props: {
            scheduleId: Number,
            initialWhat: String,
            isRecurring: Boolean,
            lastSentAt: String,
            nextOccurrence: String,
            timesSent: Number,
            when: String,
        },

        data: () => ({
            what: '',
            isEditing: false,
            deleteClickedOnce: false,
        }),

        mounted: function() {
            this.what = this.initialWhat;
        },

        methods: {
            edit: function() {
                this.isEditing = true;

                this.$nextTick(() => this.$refs.whatInput.focus());
            },

            save: function() {
                this.isEditing = false;

                if (this.what === '') {
                    this.what = this.initialWhat;

                    return;
                }

                axios.put('/api/v1/schedule/'+this.scheduleId, { 'what': this.what });
            },

            remove: function() {
                if (! this.deleteClickedOnce) {
                    this.deleteClickedOnce = true;

                    return;
                }

                axios.delete('/api/v1/schedule/'+this.scheduleId);

                this.$emit('deleted', this.scheduleId);
            },
        },

        filters: {
            secondless: function (value) {
                return value.substr(0, value.length - 3);
            },
        },
    }
</script>
