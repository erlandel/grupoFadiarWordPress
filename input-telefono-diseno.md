# Input de Teléfono - Diseño e Implementación

## 1. Descripción General

Selector de país + input numérico que emite el valor completo en formato `+53 12345678`.  
Usado en 3 formularios del proyecto:

| Ubicación | Archivo |
|---|---|
| Datos personales | `components/personalData/personalData.tsx` |
| Envío de dinero | `components/amount/amount.tsx` |
| Modal destinatario | `components/modal/modalRecipientPaymentDetails/modalRecipientPaymentDetails.tsx` |

---

## 2. Dependencias

| Librería | Versión | Propósito |
|---|---|---|
| `country-flags-dial-code` | `^1.2.0` | Lista de países con banderas SVG, códigos ISO y dial codes |
| `i18n-iso-countries` | `^7.14.0` | Traducción de nombres de países a español |
| `libphonenumber-js` | `^1.12.41` | Validación real de números telefónicos (server-side) |
| `zod` | `^4.1.13` | Schema de validación en frontend |
| `lucide-react` | `^0.554.0` | Iconos ChevronUp / ChevronDown |

---

## 3. Componente — `components/phoneInput/phoneInput.tsx`

### 3.1 Props

| Prop | Tipo | Default | Descripción |
|---|---|---|---|
| `value` | `string` | `"+53 "` | Valor completo (`+{código} {número}`) |
| `onChange` | `(value: string) => void` | — | Callback con formato `"+53 12345678"` |
| `placeholder` | `string` | `"Teléfono"` | Placeholder del input |
| `defaultCountry` | `{ name, code, phoneCode }` | `{ name: "Cuba", code: "CU", phoneCode: "+53" }` | País preseleccionado |
| `inputMode` | `string` | — | Pasar `"numeric"` para teclado numérico en mobile |
| `pattern` | `string` | — | Pasar `"[0-9]*"` |
| `dropdownDirection` | `"up" \| "down"` | `"down"` | Dirección del desplegable de países |

### 3.2 Flujo del Componente

```
Carga inicial
  └─ getCountryListMap() → lista de países { name, code, phoneCode, flag }
  └─ i18n-iso-countries (es) → traduce nombres
  └─ Sincroniza país inicial con selectedCountry

Sincronía externa (cuando value cambia desde fuera)
  └─ Si value incluye espacio: separa dialCode y número
  └─ Si value empieza con "+": busca match con phoneCode más largo
  └─ Caso especial "+1": fuerza US como país
  └─ Actualiza selectedCountry y el valor del input

Interacción usuario
  └─ Escribe número → onChange emite `"${phoneCode} ${soloDigitos}"`
  └─ Abre dropdown → busca país → selecciona → onChange emite nuevo valor
  └─ Clic fuera → cierra dropdown (clickOutside.ts)
```

### 3.3 Formato de Salida

```
+53 12345678
 ^  ^------- solo dígitos
 código
```

### 3.4 Estados

| Estado | Comportamiento |
|---|---|
| **Default** | Input vacío con placeholder "Teléfono", código `+53`, bandera de Cuba |
| **Dropdown abierto** | Buscador sticky + lista filtrable con scroll (max-h-60) |
| **Dropdown vacío** | Mensaje "No se encontraron países" |
| **Error** | El componente NO maneja errors. Se renderiza externamente `<span className="text-red-500">{errors.phone}</span>` |
| **Disabled** | No implementado (no se usa en este proyecto) |

---

## 4. Validación — `validations/personalDataSchema.ts`

```typescript
phone: z.string()
  .min(1, "El teléfono es requerido")
  .superRefine((val, ctx) => {
    // 1. Formato básico: /^\+[\d\s]+$/
    //    - Debe empezar con "+"
    //    - Solo números y espacios después

    // 2. Validación real con libphonenumber-js
    //    parsePhoneNumberFromString()

    // 3. Mensajes de error jerárquicos:
    //    - "Debe ingresar el número de teléfono completo"
    //      → hay dial code pero no número
    //    - "Número de teléfono no es posible para este país"
    //      → libphonenumber-js: !isPossible()
    //    - "Número de teléfono inválido (longitud incorrecta)"
    //      → libphonenumber-js: !isValid()
    //    - "Falta el código de país (debe empezar con +)"
    //      → no empieza con "+"
    //    - "Formato de teléfono incorrecto"
    //      → caso general
  })
```

---

## 5. Uso en Formularios (Patrón Consistente)

```tsx
// Import
import PhoneInput from "../phoneInput/phoneInput";

// State
const [formData, setFormData] = useState({ phone: "" });

// Handler
const handlePhoneChange = (value: string) => {
  setFormData((prev) => ({ ...prev, phone: value }));
  if (errors.phone) {
    setErrors((prev) => { const new = { ...prev }; delete new.phone; return new; });
  }
};

// JSX
<label>Teléfono</label>
<PhoneInput
  value={formData.phone}
  onChange={handlePhoneChange}
  placeholder="Teléfono"
  inputMode="numeric"
/>
{errors.phone && <span className="text-red-500 text-sm">{errors.phone}</span>}
```

### Uso en Modal (dropdown hacia arriba)

```tsx
<PhoneInput
  value={editData.phone}
  onChange={handlePhoneChange}
  placeholder="Teléfono"
  inputMode="numeric"
  pattern="[0-9]*"
  dropdownDirection="up"   // ← importante en modales
/>
```

---

## 6. Envío al Backend

- Endpoint: `POST /editUserUrl`
- Formato: `FormData`
- Campo: `person.cellphone1`
- Valor: string en formato `+53 12345678` (con código, sin formatear)

```typescript
// Dentro de performUpdate()
data.append("changes", JSON.stringify([
  {
    operation: "UPDATE",
    table: "persons",
    attribute: "cellphone1",
    value: formData.phone   // ej: "+53 12345678"
  }
]));
```

---

## 7. Diseño Visual

- **Layout**: `flex` horizontal, items centrados, gap `2`
- **Padding**: `px-4 py-3`
- **Border radius**: `rounded-2xl` (16px)
- **Background**: `bg-[#F5F7FA]` (gris claro)
- **Focus**: `focus-within:ring-2 focus-within:ring-accent`
- **Bandera**: `<img width="24" height="auto" />`
- **Separador**: `|` (pipe vertical)
- **Input**: `bg-transparent outline-none`, flex-1
- **Dropdown**: `absolute z-10`, `bg-white`, `rounded-2xl shadow-lg border`, `max-h-60 overflow-y-auto`
- **Ítems dropdown**: `hover:bg-gray-100`, padding `px-4 py-2`

---

## 8. Estructura de Carpetas y Archivos del Componente

```
components/
└── phoneInput/
    └── phoneInput.tsx          # Componente principal

utils/
└── clickOutside.ts             # Hook para cerrar dropdown al hacer clic fuera

validations/
└── personalDataSchema.ts       # Schema Zod con validación de teléfono

hooks/
└── myProfileRequests/
    └── usePersonalData.ts      # Hook que integra handlePhoneChange + envío
```

---

## 9. Notas Adicionales

- El `defaultCountry` es Cuba (`+53`) porque la aplicación apunta al mercado cubano.
- Para evitar conflictos con `+1` (USA/Canadá/Caribe), se fuerza `US` cuando el dial code es `+1`.
- La prioridad de matching del dial code es por longitud descendente (para que `+1-242` coincida antes que `+1`).
- El input no usa máscara visual (guiones, paréntesis). Solo dígitos.
- Las banderas se renderizan como SVG inline desde `country-flags-dial-code`.

---

## 10. Plan de Implementación para WordPress

### Requisitos

| Componente | Tecnología |
|---|---|
| Selector de país | HTML + CSS + JS vanilla |
| Input numérico | `<input type="tel">` |
| Validación frontend | JS + regex opcional |
| Validación backend | PHP + `libphonenumber-for-php` |
| Guardado | `update_user_meta($user_id, 'phone', $value)` |

### Tareas

1. **Obtener lista de países**
   - Array estático PHP con `{ code, name_es, phoneCode, flag_svg }`
   - Guardar como JSON en theme o plugin
   - Inyectar via `wp_localize_script()`

2. **HTML/CSS del componente**
   - Crear template `input-phone.php`
   - Input con `type="tel"`, selector de país con `<select>` o dropdown custom
   - Bordes redondeados, enfoque con ring

3. **JS del componente**
   - Al seleccionar país, actualizar `<span>` con código + bandera
   - Filtrar búsqueda de países
   - Solo permitir dígitos en el input
   - Emitir valor en `<input type="hidden">` con formato `+53 12345678`

4. **Validación PHP**
   - Instalar `giggsey/libphonenumber-for-php` via Composer
   - Validar: `$phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance(); $phoneUtil->isValidNumber($phoneProto);`
   - Limpiar: solo dígitos + signo más

5. **Guardado**
   - Hook `user_profile_update_errors` o `personal_options_update`
   - `update_user_meta($user_id, 'cellphone1', sanitize_text_field($_POST['phone']))`

6. **Paginación de datos del usuario**
   - Mostrar valor guardado al editar perfil
   - Formatear correctamente código + número

### Dependencias PHP Sugeridas

```json
{
  "require": {
    "giggsey/libphonenumber-for-php": "^8.13"
  }
}
```
