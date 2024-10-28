import { useState, useEffect } from "react";
import Modal from "@/Components/Modal";
import { useForm } from "@inertiajs/react";
import ErrorText from "@/Components/ErrorText";
import TextInput from "@/Components/TextInput";
import InputLabel from "@/Components/InputLabel";

export default function ModalRetrait({ openModal, setOpenModal }) {
    const [processing, setProcessing] = useState(false);
    const [operateurSelected, setOperateurSelected] = useState("");
    const { post, setData, data, errors } = useForm({
        numero: "",
        code_otp: "",
        operateur: "",
    });

    const handleClose = () => {
        setOpenModal(false);
    };

    const handleChange = (e) => {
        const { name, value } = e.target;
        setData(name, value);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        setProcessing(true);

        post(route('licence.store'), {
            onSuccess: () => {
                setProcessing(false);
                handleClose();
            },
            onError: () => {
                setProcessing(false);
            }
        });
    };

    useEffect(() => {
        setData('operateur', operateurSelected);
    }, [operateurSelected, setData]);

    return (
        <Modal show={openModal} onClose={handleClose}>
            <form onSubmit={handleSubmit} className="p-6 dark:bg-slate-700">
                <h2 className="text-lg font-semibold">
                    Confirmer le choix de votre retrait
                </h2>
                <p>
                    Êtes-vous sûr de vouloir fait le retrait ?
                </p>
                <div className="mt-4">
                    <p>
                        Choisissez un mode de paiement pour confirmer le retrait
                    </p>
                    <div className="mt-2 flex gap-3 justify-around flex-row">
                        {["Orange Money", "Wave", "Moov Money", "MTN Money"].map((operateur) => (
                            <button
                                key={operateur}
                                onClick={() => setOperateurSelected(operateur)}
                                type="button"
                                className={`text-center hover:bg-green-500 p-4 mx-auto ${operateurSelected === operateur ? "bg-green-500" : ""}`}
                            >
                                <img src={`/image/${operateur.toLowerCase().replace(' ', '')}.png`} alt={operateur} className="w-16 h-16 mx-auto" />
                                <span>{operateur}</span>
                            </button>
                        ))}
                    </div>
                    {errors.operateur && <ErrorText>{errors.operateur}</ErrorText>}
                    {operateurSelected && (
                        <div className="mt-4">
                            <InputLabel htmlFor="numero" value="Numéro" />
                            <TextInput
                                id="numero"
                                name="numero"
                                value={data.numero}
                                onChange={handleChange}
                                className="mt-1 block w-full"
                            />
                            {errors.numero && <ErrorText>{errors.numero}</ErrorText>}
                            {operateurSelected === "Orange Money" && (
                                <div className="mt-4">
                                    <InputLabel htmlFor="code_otp" value="Code OTP" />
                                    <TextInput
                                        id="code_otp"
                                        name="code_otp"
                                        value={data.code_otp}
                                        onChange={handleChange}
                                        className="mt-1 block w-full"
                                    />
                                    {errors.code_otp && <ErrorText>{errors.code_otp}</ErrorText>}
                                </div>
                            )}
                        </div>
                    )}
                </div>
                <div className="flex space-x-4 mt-4">
                    <button
                        type="button"
                        onClick={handleClose}
                        className="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                    >
                        Annuler
                    </button>
                    <button
                        type="submit"
                        className="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                        disabled={processing}
                    >
                        {processing ? 'En cours...' : 'Confirmer'}
                    </button>
                </div>
            </form>
        </Modal>
    );
}
