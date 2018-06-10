# Meel.me
Email yourself.

## Timezones
The `next_occurrence` field in `EmailSchedule`, and the `sent_at` field in `EmailScheduleHistory` are in the timezone of the `User` they belong to. All other datetimes are in the timezone of the server (Europe/Amsterdam).
