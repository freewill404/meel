<template>
    <div class="panel max-w-xs mx-auto text-sm">
        <label class="block">
            What?
            <input type="text" :value="what" class="field text-sm" disabled>
        </label>

        <label class="block mt-8">
            When?
            <input type="text" :value="when" class="field text-sm" disabled>
        </label>

        <button class="btn block ml-auto mt-4">Meel</button>
    </div>
</template>

<script>
    export default {

        data: () => ({
            examples: [
                'Make a dentist appointment|Yearly in May',
                'Call grandma|Tomorrow',
                'Sign up for Meel.me|Now',
                'Do my taxes|On the 15th of April',
                'Clean the house|Every second saturday of the month',
                'Do yoga|Every day at 20:00',
                'Buy a flying car|in 2025',
                'Replace smoke detector batteries|Every two years',
            ],
            what: 'Start emailing yourself',
            when: 'Today',
            index: 0,
        }),

        mounted: function () {
            setInterval(() => {
                const whenAndWhat = this.examples[this.index++].split('|');

                this.what = whenAndWhat[0];
                this.when = whenAndWhat[1];

                if (this.index >= this.examples.length) {
                    this.index = 0;
                }
            }, 4000);
        },

    }
</script>