import { useEffect, useState } from "react";
import '../../css/style.css'

export default function StatsCounter() {
    // État pour les statistiques finales
    const [companies, setCompanies] = useState(0);
    const [countries, setCountries] = useState(0);
    const [affiliations, setAffiliations] = useState(0);
    const [utilisateurs, setUtilisateurs] = useState(0);

    // Valeurs cibles pour les compteurs
    const targetCompanies = 20;
    const targetCountries = 3;
    const targetAffiliations = 470;
    const targetUtilisateurs = 1000;

    // Fonction d'incrémentation
    const incrementCounter = (setCounter, target, delay = 20) => {
        let count = 0;
        const step = Math.ceil(target / 800); // Détermine la vitesse d'incrémentation
        const interval = setInterval(() => {
            count += step;
            if (count >= target) {
                count = target;
                clearInterval(interval);
            }
            setCounter(count);
        }, delay);
    };

    // Démarrer les compteurs une fois que le composant est monté
    useEffect(() => {
        incrementCounter(setCompanies, targetCompanies);
        incrementCounter(setCountries, targetCountries);
        incrementCounter(setUtilisateurs, targetUtilisateurs);
        incrementCounter(setAffiliations, targetAffiliations);
    }, []);

    return (
        <section className="primary dark:bg-gray-900">
            <div className="max-w-screen-xl px-4 py-8 mx-auto text-center lg:py-16 lg:px-6">
                <h2 className="text-3xl font-extrabold text-gray-900 dark:text-white mb-6">Statistiques de Medine</h2>
                <dl className="grid max-w-screen-md gap-8 mx-auto text-gray-900 sm:grid-cols-4 dark:text-white">
                    {/* Total Compagnies */}
                    <div className="flex flex-col items-center justify-center">
                    <dt className="mb-2 text-5xl md:text-7xl font-extrabold">{utilisateurs}</dt>
                    <dd className="font-light text-gray-500 dark:text-gray-400">Total Utilisateurs</dd>
                    </div>
                    <div className="flex flex-col items-center justify-center">
                        <dt className="mb-2 text-5xl md:text-7xl font-extrabold">{companies}</dt>
                        <dd className="font-light text-gray-500 dark:text-gray-400">Total Compagnies</dd>
                    </div>
                    {/* Total Pays */}
                    <div className="flex flex-col items-center justify-center">
                        <dt className="mb-2 text-5xl md:text-7xl font-extrabold">{countries}</dt>
                        <dd className="font-light text-gray-500 dark:text-gray-400">Total Pays</dd>
                    </div>
                    {/* Total Affiliation */}
                    <div className="flex flex-col items-center justify-center">
                        <dt className="mb-2 text-5xl md:text-7xl font-extrabold">+{affiliations}</dt>
                        <dd className="font-light text-gray-500 dark:text-gray-400">Total Tickets vendus</dd>
                    </div>
                </dl>
            </div>
        </section>
    );
}
