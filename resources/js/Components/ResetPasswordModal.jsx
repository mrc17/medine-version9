import Modal from './Modal';
import { useForm } from '@inertiajs/react';

export default function ResetPasswordModal({ show, onClose, selectedEmployee }) {
    const { patch, processing, reset, errors } = useForm();

    const resetPassword = (e) => {
        e.preventDefault();
        patch(route('employee.resetPassword', { employee: selectedEmployee.id }), {
            preserveScroll: true,
            onSuccess: () => {
                reset();
                onClose();
            },
            onError: (errors) => {
                console.log(errors); // Handle any errors if necessary
            },
        });
    };

    return (
        <Modal show={show} onClose={onClose}>
            <form onSubmit={resetPassword} className="p-4 text-center bg-white rounded-lg shadow dark:bg-gray-800">
                <h3 className="text-lg font-medium text-gray-900 dark:text-white">Réinitialiser le Mot de Passe</h3>
                <p className="mb-4 text-gray-500 dark:text-gray-300">
                    Êtes-vous sûr de vouloir réinitialiser le mot de passe pour <strong>{selectedEmployee?.user?.nom}</strong> ?
                </p>
                <div className="flex justify-center items-center space-x-4">
                    <button type="button" onClick={onClose} className="py-2 px-3 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-200 hover:bg-gray-100">Non, Annuler</button>
                    <button type="submit" disabled={processing} className="py-2 px-3 text-sm font-medium text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        Oui, Réinitialiser
                    </button>
                </div>
            </form>
        </Modal>
    );
}
