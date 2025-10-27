<template>
    <div id="donationModal" class="modal">
        <div class="modal-content">
            <h4>Make a Donation</h4>
            <p>You are donating to: <strong>{{ projectName }}</strong></p>
            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">attach_money</i>
                    <input id="amount" type="number" v-model.number="amount">
                    <label for="amount">Amount (₦)</label>
                </div>
            </div>
            <p>Choose Payment Method:</p>
            <button class="btn waves-effect waves-light indigo" @click="processDonation('paystack')">
                Pay with Paystack
            </button>
            <button class="btn waves-effect waves-light blue" @click="processDonation('stripe')">
                Pay with Stripe
            </button>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Cancel</a>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        projectId: {
            type: Number,
            required: true
        },
        projectName: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            amount: null,
        };
    },
    methods: {
        async processDonation(gateway) {
            if (!this.amount || this.amount <= 0) {
                M.toast({html: 'Please enter a valid amount!'});
                return;
            }

            // In a real app, this would trigger the payment gateway's flow (e.g., Paystack Popup)
            // and then send the transaction reference to your backend for verification.
            console.log(`Processing ₦${this.amount} for project ${this.projectId} via ${gateway}`);

            try {
                // Example API call to backend after successful payment
                // const response = await axios.post('/api/donations', {
                //     project_id: this.projectId,
                //     amount: this.amount,
                //     gateway: gateway,
                //     reference: 'PAYMENT_GATEWAY_REFERENCE'
                // });
                M.toast({html: 'Thank you for your donation!'});
                const modalInstance = M.Modal.getInstance(document.getElementById('donationModal'));
                modalInstance.close();

            } catch (error) {
                console.error("Error submitting donation:", error);
                M.toast({html: 'An error occurred. Please try again.'});
            }
        }
    },
    mounted() {
        // Initialize Materialize modal
        const elem = document.getElementById('donationModal');
        M.Modal.init(elem);
    }
}
</script>