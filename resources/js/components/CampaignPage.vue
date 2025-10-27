<template>
    <div>
        <div class="center-align" v-if="timeLeft.total > 0">
            <h3>Time Remaining</h3>
            <h2 class="indigo-text">{{ timeLeft.days }}d {{ timeLeft.hours }}h {{ timeLeft.minutes }}m {{ timeLeft.seconds }}s</h2>
        </div>
        <div v-else class="center-align">
            <h2 class="red-text">This campaign has ended.</h2>
        </div>

        <div class="section">
            <h4>Overall Progress</h4>
            <div class="progress">
                <div class="determinate" :style="{ width: progressPercentage + '%' }"></div>
            </div>
            <p class="flow-text">
                <strong>₦{{ formatCurrency(liveStats.total_raised) }}</strong> raised of <strong>₦{{ formatCurrency(campaign.goal_amount) }}</strong> goal
                <span class="right"><strong>{{ liveStats.donor_count }}</strong> Donors</span>
            </p>
        </div>

        <div class="section" v-if="campaign.challenges.length">
            <h4>Active Challenges</h4>
            </div>

        <div class="section">
            <h4>Support a Project</h4>
            </div>
    </div>
</template>

<script>
export default {
    props: ['initialCampaign'],
    data() {
        return {
            campaign: JSON.parse(this.initialCampaign),
            liveStats: {
                total_raised: 0,
                donor_count: 0,
            },
            timeLeft: { total: 0, days: 0, hours: 0, minutes: 0, seconds: 0 },
            timer: null,
            poller: null,
        }
    },
    computed: {
        progressPercentage() {
            if (this.campaign.goal_amount > 0) {
                return (this.liveStats.total_raised / this.campaign.goal_amount) * 100;
            }
            return 0;
        }
    },
    methods: {
        startCountdown() {
            const endTime = new Date(this.campaign.end_time).getTime();
            this.timer = setInterval(() => {
                const now = new Date().getTime();
                const distance = endTime - now;
                this.timeLeft.total = distance;
                this.timeLeft.days = Math.floor(distance / (1000 * 60 * 60 * 24));
                this.timeLeft.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                this.timeLeft.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                this.timeLeft.seconds = Math.floor((distance % (1000 * 60)) / 1000);
                if (distance < 0) {
                    clearInterval(this.timer);
                }
            }, 1000);
        },
        async fetchLiveStats() {
            try {
                const response = await axios.get(`/api/campaigns/${this.campaign.slug}/status`);
                this.liveStats = response.data;
            } catch (error) {
                console.error("Could not fetch live stats", error);
            }
        },
        formatCurrency(amount) {
            return Number(amount).toLocaleString();
        }
    },
    mounted() {
        this.startCountdown();
        this.fetchLiveStats(); // Initial fetch
        this.poller = setInterval(this.fetchLiveStats, 15000); // Poll for new data every 15 seconds
    },
    beforeDestroy() {
        clearInterval(this.timer);
        clearInterval(this.poller);
    }
}
</script>