import './bootstrap';
import Alpine from 'alpinejs'
import AlpineFloatingUI from '@awcodes/alpine-floating-ui'
// import NotificationsAlpinePlugin from '../../vendor/filament/notifications/dist/module.esm'
 
Alpine.plugin(AlpineFloatingUI)
Alpine.plugin(NotificationsAlpinePlugin)
 
window.Alpine = Alpine
 
Alpine.start()

// filepath: /g:/projects/loan-management-system/resources/js/app.js
document.addEventListener('alpine:init', () => {
   Alpine.data('loanManagement', () => ({
       showInstallmentsModal(loanId) {
           // Your logic to show the modal
           console.log(`Showing installments modal for loan ID: ${loanId}`);
           // Example: Open a modal using a library like Bootstrap or Tailwind UI
           // $('#installmentsModal').modal('show');
       }
   }));
});

import { installmentPayment } from './components/installment-payment';

Alpine.data('installmentPayment', installmentPayment);
Alpine.start();