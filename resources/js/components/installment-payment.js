export function installmentPayment() {
    return {
        showPaymentForm: false,
        selectedInstallmentId: null,
        paymentAmount: 0,
        maxPaymentAmount: 0,

        openPaymentForm(installmentId, remainingBalance) {
            this.selectedInstallmentId = installmentId;
            this.maxPaymentAmount = remainingBalance;
            this.paymentAmount = remainingBalance;
            this.showPaymentForm = true;
        },

        submitPayment() {
            fetch(`/installments/${this.selectedInstallmentId}/pay`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    payment_amount: this.paymentAmount
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                }
            });
        }
    }
}