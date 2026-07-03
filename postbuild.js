import { readFileSync, writeFileSync } from 'fs';
const header = `/*
Theme Name: Grupo Fadiar Theme
Description: Tema personalizado para Grupo Fadiar
Author: Erlan
Version: 1.0.0
*/\n`;
writeFileSync('style.css', header + readFileSync('style.css', 'utf8'));
