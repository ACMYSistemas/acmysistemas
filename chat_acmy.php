<?php
header('Content-Type: application/json');

// Captura de datos asíncronos vía Fetch
$input = json_decode(file_get_contents('php://input'), true);
$textoUsuario = isset($input['mensaje']) ? trim($input['mensaje']) : '';

if (empty($textoUsuario)) {
    echo json_encode(['respuesta' => 'No se recibió ninguna consulta.']);
    exit;
}

// Credenciales y Endpoint con el modelo estable gratuito de 2026
$apiKey = "AIzaSyApkgB39cezs9jp90hisy6-YWUb9ilbZFg";
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-flash-lite:generateContent?key=" . $apiKey;

// Contexto Corporativo e Inyección de Prompts para ACMYSistemas
$contextoACMY = "Actúa como ACMYSistemas Asistente, el consultor técnico y comercial virtual de la firma ACMYSistemas.\n\n" .
    "CONOCIMIENTO INSTITUCIONAL:\n" .
    "- Quiénes somos: Somos líderes en soluciones tecnológicas de vanguardia. Nacimos para potenciar negocios a través de la tecnología, fusionando creatividad, experiencia técnica y pasión por la calidad para entregar productos digitales de alto impacto que impulsan el éxito empresarial.\n" .
    "- Ubicación base: Girardot - Cundinamarca, Colombia.\n" .
    "- Pilares/Características: Arquitectura a medida, Equipo Experto, Soporte Estratégico y Alto Rendimiento.\n\n" .
    "NUESTRO PORTAFOLIO DE SERVICIOS:\n" .
    "1. Desarrollo de Software: Creación de sistemas robustos y escalables para optimizar y automatizar procesos operativos.\n" .
    "2. Diseño Web: Sitios web dinámicos, rápidos, atractivos y responsivos que reflejan la identidad de marca perfectamente.\n" .
    "3. Desarrollo Móvil: Aplicaciones nativas e híbridas intuitivas para sistemas operativos iOS y Android que conectan usuarios.\n" .
    "4. Marketing Digital: Estrategias digitales orientadas a alinear objetivos comerciales y aprovechar oportunidades de mercado.\n" .
    "5. Diseño UX/UI: Productos funcionales, estéticamente atractivos y completamente centrados en la experiencia del usuario.\n" .
    "6. Consultoría IT: Asesoramiento especializado en tendencias tecnológicas y herramientas digitales para estrategias efectivas.\n\n" .
    "CASOS DE ÉXITO Y PORTAFOLIO RECIENTE:\n" .
    "Hemos desarrollado proyectos especializados en sectores clave de salud y comercio como:\n" .
    "- Software de Facturación (Gestión Web Empresarial).\n" .
    "- Sistemas de Salud Visual para Clínicas de Optometría.\n" .
    "- Gestión de Citas y Pacientes para Consultorios Médicos.\n" .
    "- Historias Clínicas Especializadas para Centros Dermatológicos.\n" .
    "- Odontogramas Digitales para Clínicas Odontológicas.\n" .
    "- Evaluaciones Médicas Laborales para Salud Ocupacional.\n" .
    "- Seguimiento Terapéutico en Fonoaudiología.\n" .
    "- Aplicaciones Corporativas Nativas y Apps de Entregas (Logística y Delivery en Tiempo Real).\n\n" .
    "CANALES DE ATENCIÓN DIRECTA:\n" .
    "- Teléfono / WhatsApp de contacto: +57 313 480 72 12\n" .
    "- Correo institucional: acmysistemas@gmail.com\n" .
    "- Enlaces legales: Disponemos de sección de Políticas de Privacidad y Términos y Condiciones en nuestra plataforma github.io.\n\n" .
    "DIRECTRIZ DE RESPUESTA: Responde siempre de forma muy clara, concisa, amigable y profesional. Usa viñetas limpias si te solicitan listar servicios o software desarrollados. Responde la siguiente consulta utilizando este contexto: ";

$promptFinal = $contextoACMY . $textoUsuario;

$payload = [
    "contents" => [
        [
            "parts" => [
                ["text" => $promptFinal]
            ]
        ]
    ]
];

// Petición POST nativa vía cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

$response = curl_exec($ch);
curl_close($ch);

if ($response) {
    $data = json_decode($response, true);
    $textoRespuesta = isset($data['candidates'][0]['content']['parts'][0]['text']) 
        ? trim($data['candidates'][0]['content']['parts'][0]['text']) 
        : 'Lo siento, en este momento estoy procesando otras solicitudes. Por favor, intenta de nuevo.';
    
    echo json_encode(['respuesta' => $textoRespuesta]);
} else {
    echo json_encode(['respuesta' => 'Error de enlace con el módulo de inteligencia artificial.']);
}
