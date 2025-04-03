import React, { useState } from "react";
import { BASE_URL } from "C:/xampp/htdocs/PROYECTO4B-1/REACT/Interfaz/config";
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  Alert,
  StyleSheet,
} from "react-native";

export default function EditProveedorScreen({ route, navigation }) {
  const { proveedor } = route.params; // Datos del proveedor
  const [name, setName] = useState(proveedor.name);
  const [contactInfo, setContactInfo] = useState(proveedor.contact_info);
  const [address, setAddress] = useState(proveedor.address);

  const handleEditProveedor = () => {
    // Validación de campos vacíos
    if (!name.trim() || !contactInfo.trim() || !address.trim()) {
      Alert.alert("Error", "Todos los campos son obligatorios.");
      return;
    }

    fetch(`${BASE_URL}/PROYECTO4B-1/phpfiles/react/supplier_api.php`, {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        id_supplier: proveedor.id_supplier,
        name,
        contact_info: contactInfo,
        address,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          Alert.alert("Éxito", "Proveedor actualizado exitosamente.");
          navigation.goBack();
        } else {
          Alert.alert(
            "Error",
            data.error || "No se pudo actualizar el proveedor."
          );
        }
      })
      .catch((error) => {
        console.error("Error al actualizar proveedor:", error);
        Alert.alert("Error", "Ocurrió un error al actualizar el proveedor.");
      });
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Editar Proveedor</Text>
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
      <TouchableOpacity style={styles.button} onPress={handleEditProveedor}>
        <Text style={styles.buttonText}>Guardar Cambios</Text>
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
