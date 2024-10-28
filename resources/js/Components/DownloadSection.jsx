import { Link } from "@inertiajs/react";
import '../../css/style.css'

export default function DownloadSection() {
    return (
        <section className="bg-gray-50 secondary py-12">
            <div className="max-w-screen-xl px-4 mx-auto text-center">
                <h2 className="text-3xl font-extrabold leading-tight text-primary text-white">
                    Téléchargez l'application Medine
                </h2>
                <p className="mt-4 text-lg font-light text-primary">
                    Disponible sur Play Store et App Store. Achetez vos tickets de voyage en toute simplicité.
                </p>

                <div className="mt-8 flex justify-center space-x-4">
                    <Link href="https://play.google.com/store/apps/details?id=com.axeltoure.medine" target="_blank">
                        <img src="/images/asset/Play_Store.webp" alt="Disponible sur Google Play" className="h-16" />
                    </Link>

                    <Link href="https://apps.apple.com/us/app/m%C3%A9dine-ticket-de-voyage/id6557028281" target="_blank">
                        <img src="/images/asset/App_Store.webp" alt="Disponible sur l'App Store" className="h-16" />
                    </Link>
                </div>

                <p className="mt-4 text-sm text-primary">
                    Scannez vos tickets de voyage, gérez vos achats et suivez vos trajets avec l'application Medine.
                </p>
            </div>
        </section>
    );
}
