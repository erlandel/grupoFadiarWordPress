# Guía de Integración con Figma

## 🎨 Diseño
**URL:** [Grupo Fadiar - Diseño](https://www.figma.com/design/Swp6ePbHeVrY3J7ZuQ78xL/Grupo-Fadiar--copia-?node-id=0-1&p=f&t=z3tz3QXub4qcl4wt-0)

## 🛠️ Pasos para la Conexión y Maquetación
1. **Configuración:** El token de acceso personal debe estar configurado en `C:\Users\Erlan\.open_code\mcp-config.json`.
2. **Recursos:** Descargar manualmente los assets (imágenes, iconos) y ubicarlos en `public/images/`.
3. **Extracción:** Utilizar la API de Figma para obtener referencias de:
   - Dimensiones y posicionamiento.
   - Estilos (colores, tipografía, radios).
4. **Implementación:** Maquetar utilizando las clases de **Tailwind CSS v4** que sean más similares y coherentes con el diseño, evitando valores arbitrarios siempre que sea posible.
5. **Comunicación:** En caso de dudas sobre la interpretación de un elemento o falta de información, se consultará directamente al usuario antes de implementar.
