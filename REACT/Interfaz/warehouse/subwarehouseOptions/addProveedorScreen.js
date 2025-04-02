import React, { useState } from "react";
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  Alert,
  StyleSheet,
} from "react-native";

export default function AddProveedorScreen({ route, navigation }) {
  const [name, setName] = useState("");
  const [contactInfo, setContactInfo] = useState("");
  const [address, setAddress] = useState("");

  const handleAddProveedor = () => {
    // Validación de campos vacíos
    if (!name.trim() || !contactInfo.trim() || !address.trim()) {
      Alert.alert("Error", "Todos los campos son obligatorios.");
      return;
    }

    fetch("http://localhost/PROYECTO4B-1/phpfiles/react/supplier_api.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ name, contact_info: contactInfo, address }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          Alert.alert("Éxito", "Proveedor añadido exitosamente.");
          navigation.goBack();
        } else {
          Alert.alert("Error", data.error || "No se pudo añadir el proveedor.");
        }
      })
      .catch((error) => {
        console.error("Error al añadir proveedor:", error);
        Alert.alert("Error", "Ocurrió un error al añadir el proveedor.");
      });
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Añadir Proveedor</Text>
      <TextInput
        style={styles.input}
        placeholder="Nombre del Proveedor"
        value={name}
        onChangeText={setName}
      />
      <TextInput
        style={styles.input}
        placeholder="Información de Contacto"
        value={contactInfo}
        onChangeText={setContactInfo}
      />
      <TextInput
        style={styles.input}
        placeholder="Dirección"
        value={address}
        onChangeText={setAddress}
      />
      <TouchableOpacity style={styles.button} onPress={handleAddProveedor}>
        <Text style={styles.buttonText}>Guardar</Text>
      </TouchableOpacity>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, padding: 20, backgroundColor: "white" },
  title: { fontSize: 24, fontWeight: "bold", marginBottom: 20 },
  input: {
    borderWidth: 1,
    borderColor: "#ccc",
    borderRadius: 10,
    padding: 10,
    marginBottom: 20,
    fontSize: 16,
  },
  button: {
    backgroundColor: "#4CAF50",
    padding: 15,
    borderRadius: 10,
    alignItems: "center",
  },
  buttonText: { color: "white", fontSize: 18, fontWeight: "bold" },
});
