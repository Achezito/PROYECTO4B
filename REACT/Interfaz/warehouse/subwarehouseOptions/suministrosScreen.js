import React, { useEffect, useState } from "react";
import {
  ActivityIndicator,
  Alert,
  FlatList,
  StyleSheet,
  Text,
  TouchableOpacity,
  View,
} from "react-native";

export default function SuministrosScreen({ route, navigation }) {
  const { id_sub_warehouse } = route.params || {}; // ID del subalmacén (si se pasa como parámetro)
  const [suministros, setSuministros] = useState([]);
  const [loading, setLoading] = useState(true);

  // Función para cargar los suministros desde la API
  const fetchSuministros = async () => {
    try {
      setLoading(true);
      const url = id_sub_warehouse
        ? `http://localhost/PROYECTO4B-1/phpfiles/react/supply_api.php?id_sub_warehouse=${id_sub_warehouse}`
        : `http://localhost/PROYECTO4B-1/phpfiles/react/supply_api.php`;

      const response = await fetch(url);
      if (!response.ok) {
        throw new Error(
          `Error en la respuesta del servidor: ${response.status}`,
        );
      }

      const data = await response.json();
      if (Array.isArray(data)) {
        setSuministros(data);
      } else {
        throw new Error("La respuesta no es un arreglo válido.");
      }
    } catch (error) {
      console.error("Error al cargar suministros:", error);
      Alert.alert(
        "Error",
        "No se pudieron cargar los suministros. Intenta nuevamente.",
      );
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchSuministros();
  }, []);

  // Renderizar cada suministro en la lista
  const renderItem = ({ item }) => (
    <TouchableOpacity style={styles.card}>
      <Text style={styles.cardTitle}>
        Suministro: {item.supply_name || "Sin nombre"}
      </Text>
      <Text style={styles.cardText}>
        Descripción: {item.supply_description || "Sin descripción"}
      </Text>
      <Text style={styles.cardText}>
        Cantidad: {item.quantity || "No especificada"}
      </Text>
      <Text style={styles.cardText}>
        Proveedor: {item.supplier_name || "Sin proveedor"}
      </Text>
      <Text style={styles.cardText}>
        Contacto: {item.supplier_contact || "Sin contacto"}
      </Text>
      <Text style={styles.cardText}>
        Dirección: {item.supplier_address || "Sin dirección"}
      </Text>
      <Text style={styles.cardText}>
        Ubicación: {item.subwarehouse_location || "Sin ubicación"}
      </Text>
      <Text style={styles.cardText}>
        Capacidad: {item.subwarehouse_capacity || "No especificada"}
      </Text>
    </TouchableOpacity>
  );

  return (
    <View style={styles.container}>
      <Text style={styles.title}>
        {id_sub_warehouse
          ? `Suministros del Subalmacén ${id_sub_warehouse}`
          : "Todos los Suministros"}
      </Text>

      {loading ? (
        <ActivityIndicator size="large" color="#4CAF50" />
      ) : (
        <FlatList
          data={suministros}
          keyExtractor={(item) => item.id_supply.toString()}
          renderItem={renderItem}
          ListEmptyComponent={
            <Text style={styles.emptyText}>
              No hay suministros disponibles.
            </Text>
          }
        />
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, padding: 20, backgroundColor: "white" },
  title: { fontSize: 24, fontWeight: "bold", marginBottom: 20 },
  card: {
    padding: 15,
    marginBottom: 10,
    backgroundColor: "#f9f9f9",
    borderRadius: 10,
  },
  cardTitle: { fontSize: 18, fontWeight: "bold" },
  cardText: { fontSize: 16 },
  emptyText: {
    textAlign: "center",
    fontSize: 16,
    color: "gray",
    marginTop: 20,
  },
});
