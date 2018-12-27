<template>
    <div>
        <email-schedule v-for="schedule in schedules"
            :key="schedule.obfuscatedId"
            @deleted="scheduleDeleted($event)"
            :schedule-id="schedule.obfuscatedId"
            :is-recurring="schedule.recurring"
            :times-sent="schedule.timesSent"
            :when="schedule.when"
            :initial-what="schedule.what"
            :next-occurrence="schedule.nextOccurrence"
            :last-sent-at="schedule.lastSentAt"
        ></email-schedule>
    </div>
</template>

<script>
    export default {

        props: {
            type: String,
        },

        data: () => ({
            schedules: [],
        }),

        mounted: function () {
            axios.get(`/api/v1/schedules/${this.type}`).then(response => {
                console.log(response.data.data);
                this.schedules = response.data.data;
            });
        },

        methods: {
            scheduleDeleted(id) {
                const index = this.schedules.findIndex(i => i.obfuscatedId === id);

                this.schedules.splice(index, 1);

                if (this.schedules.length === 0) {
                    document.getElementById('no-schedules-drawing').style.display = '';
                }
            },
        }
    }
</script>
