import { default as React, useEffect, useRef, useState } from "react";
import { Animated, Dimensions, StyleSheet, Text, TouchableOpacity, View } from "react-native";
import Icon from "react-native-vector-icons/MaterialCommunityIcons";

export default function App() {
  const [menuOpen, setMenuOpen] = useState(false);
  const [screenWidth, setScreenWidth] = useState(Dimensions.get("window").width); // Obtener el ancho de la pantalla
  const translateX = useRef(new Animated.Value(-screenWidth)).current; // Usar el ancho de la pantalla en la posición inicial

  // Actualizar el ancho de la pantalla si cambia (en caso de rotación, etc.)
  useEffect(() => {
    const onChange = ({ window }) => {
      setScreenWidth(window.width); // Actualiza el ancho si cambia
    };

    const subscription = Dimensions.addEventListener("change", onChange);

    // Limpiar el listener cuando el componente se desmonte
    return () => subscription.remove();
  }, []);

  const toggleMenu = () => {
    Animated.timing(translateX, {
      toValue: menuOpen ? -screenWidth : 0, // Si está abierto, lo oculta; si está cerrado, lo muestra
      duration: 300,
      useNativeDriver: true,
    }).start();
    setMenuOpen(!menuOpen);
  };

  return (
    <View style={styles.container}>
      {/* Botón para abrir/cerrar el menú */}
      <TouchableOpacity onPress={toggleMenu} style={styles.menuButton}>
        <Icon name="menu" size={30} color="white" />
      </TouchableOpacity>

      {/* Menú lateral animado */}
      <Animated.View style={[styles.sidebar, { transform: [{ translateX }] }]}>
        <Text style={styles.menuItem}>🏠 Inicio</Text>
        <Text style={styles.menuItem}>👤 Perfil</Text>
        <Text style={styles.menuItem}>⚙️ Configuración</Text>
        <TouchableOpacity onPress={toggleMenu} style={styles.closeButton}>
          <Icon name="close" size={30} color="white" />
        </TouchableOpacity>
      </Animated.View>
    </View>
  );
}

// Estilos
const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: "#f5f5f5",
    justifyContent: "center",
    alignItems: "flex-start",
    position
  },
  menuButton: {
    top: 50,
    left: 20,
    backgroundColor: "#007bff",
    padding: 10,
    borderRadius: 5,
  },
  sidebar: {
    position: "absolute",
    top: 1,
    left: 0,
    width: "80%", // Puedes dejarlo como un porcentaje o fijar un valor específico
    height: "100%",
    backgroundColor: "#333",
    paddingTop: 60,
    paddingLeft: 20,
    zIndex: 1,
    },
  menuItem: {
    color: "white",
    fontSize: 18,
    marginBottom: 20,
  },
  closeButton: {
    position: "absolute",
    top: 10,
    right: 10,
    padding: 10,
  },
});
