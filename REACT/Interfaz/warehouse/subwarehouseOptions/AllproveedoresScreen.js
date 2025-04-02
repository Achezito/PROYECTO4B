import React, { useEffect, useState } from "react";
import {
  FlatList,
  StyleSheet,
  Text,
  TouchableOpacity,
  View,
} from "react-native";

export default function ProveedoresScreen({ navigation }) {
  const [proveedores, setProveedores] = useState([]);

  // Cargar los proveedores desde la API
  useEffect(() => {
    fetch("http://localhost/PROYECTO4B-1/phpfiles/react/supplier_api.php")
      .then((response) => response.json())
      .then((data) => setProveedores(data))
      .catch((error) => console.error("Error al cargar proveedores:", error));
  }, []);

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Proveedores</Text>

      {/* Botón para añadir un proveedor */}
      <TouchableOpacity
        style={styles.addButton}
        onPress={() => navigation.navigate("AddProveedorScreen")}
      >
        <Text style={styles.addButtonText}>Añadir Proveedor</Text>
      </TouchableOpacity>

      {/* Lista de proveedores */}
      <FlatList
        data={proveedores}
        keyExtractor={(item) => item.id_supplier.toString()}
        renderItem={({ item }) => (
          <TouchableOpacity
            style={styles.card}
            onPress={() =>
              navigation.navigate("EditProveedorScreen", { proveedor: item })
            }
          >
            <Text style={styles.cardTitle}>{item.name}</Text>
            <Text style={styles.cardText}>Contacto: {item.contact_info}</Text>
            <Text style={styles.cardText}>Dirección: {item.address}</Text>
          </TouchableOpacity>
        )}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 20,
    backgroundColor: "#FFF3E0", // Fondo naranja claro
  },
  title: {
    fontSize: 24,
    fontWeight: "bold",
    marginBottom: 20,
    color: "#E65100", // Naranja oscuro
  },
  addButton: {
    backgroundColor: "#FF9800", // Botón naranja
    padding: 15,
    borderRadius: 10,
    alignItems: "center",
    marginBottom: 20,
  },
  addButtonText: {
    color: "white",
    fontSize: 18,
    fontWeight: "bold",
  },
  card: {
    padding: 15,
    marginBottom: 10,
    backgroundColor: "#FFE0B2", // Fondo de las tarjetas naranja claro
    borderRadius: 10,
    borderWidth: 1,
    borderColor: "#FFB74D", // Borde naranja
  },
  cardTitle: {
    fontSize: 18,
    fontWeight: "bold",
    color: "#E65100", // Naranja oscuro
  },
  cardText: {
    fontSize: 16,
    color: "#BF360C", // Naranja más oscuro
  },
});
