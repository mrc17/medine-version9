import React from 'react'
import '../../css/style.css'

function Fonctionnalites() {
    return (
        <section id='fonctionnalites' className="primary">
            <div>
                <div className="gap-16 items-center py-8 px-4 mx-auto max-w-screen-xl lg:grid lg:grid-cols-2 lg:py-16 lg:px-6">
                    <div className="font-light text-gray-500 sm:text-lg dark:text-gray-400">
                        <h2 className="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Découvrez comment Médine simplifie vos réservations</h2>
                        <p className="mb-4">Regardez notre vidéo explicative pour voir Médine en action. Apprenez comment réserver facilement vos tickets de voyage en ligne, suivre vos réservations, et gérer vos déplacements avec une interface simple et efficace. Essayez notre application et voyez comment elle peut vous faire gagner du temps et de l'énergie.</p>
                    </div>
                    <div className="flex items-center justify-center mt-8">
                        <video className="w-full rounded-lg" style={{ width: '300px', height: 'auto' }} autoPlay loop muted>
                            <source src="images/asset/demo_2.mp4" type="video/mp4" />
                            <source src="images/asset/demo_2.mp4" type="video/ogg" />
                        </video>
                    </div>
                </div>
            </div>
            <div>
                <div className="gap-16 items-center py-8 px-4 mx-auto max-w-screen-xl lg:grid lg:grid-cols-2 lg:py-16 lg:px-6">
                <div className="flex items-center justify-center mt-8">
                        <video className="w-full rounded-lg" style={{ width: '250px', height: 'auto' }} autoPlay loop muted>
                            <source src="images/asset/demo_1.mp4" type="video/mp4" />
                            <source src="images/asset/demo_1.mp4" type="video/ogg" />
                        </video>
                    </div>
                    <div className="font-light text-gray-500 sm:text-lg dark:text-gray-400">
                        <h2 className="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Médine devient un acteur clé du transport en Côte d'Ivoire
                        par Médine - Novembre 2024</h2>
                        <p className="mb-4">Le 24 Novembre 2024, Médine, application de réservation de tickets en ligne, révolutionne les déplacements en Côte d'Ivoire. En collaborant avec les compagnies de transport, Médine simplifie la planification de voyages tout en garantissant des transactions sécurisées. Cette avancée marque une étape clé dans la digitalisation du secteur des transports du pays.</p>
                    </div>
                </div>
            </div>
        </section>
    )
}

export default Fonctionnalites
