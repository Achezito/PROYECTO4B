import React, { useEffect, useState } from "react";
import {
  ActivityIndicator,
  Alert,
  FlatList,
  StyleSheet,
  Text,
  TextInput,
  TouchableOpacity,
  View,
} from "react-native";

export default function SuministrosScreen({ route, navigation }) {
  const { id_sub_warehouse } = route.params || {}; // ID del subalmacén (si se pasa como parámetro)
  const [suministros, setSuministros] = useState([]);
  const [filteredSuministros, setFilteredSuministros] = useState([]); // Suministros filtrados
  const [searchText, setSearchText] = useState(""); // Texto de búsqueda
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
        setFilteredSuministros(data); // Inicializa los suministros filtrados
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

  // Función para filtrar suministros
  useEffect(() => {
    const filtered = suministros.filter((item) => {
      const material = item["material_model"]?.toLowerCase() || "";
      const materialType = item["material_type"]?.toLowerCase() || "";
      const supplier = item["supplier_name"]?.toLowerCase() || "";
      const orderId = item["order_id"]?.toString() || "";

      return (
        material.includes(searchText.toLowerCase()) ||
        materialType.includes(searchText.toLowerCase()) ||
        supplier.includes(searchText.toLowerCase()) ||
        orderId.includes(searchText.toLowerCase())
      );
    });
    setFilteredSuministros(filtered);
  }, [searchText, suministros]);

  // Renderizar cada suministro en la lista
  const renderItem = ({ item }) => (
    <TouchableOpacity style={styles.card}>
      <Text style={styles.cardTitle}>
        {item.material_model || "Material sin nombre"}
      </Text>
      <Text style={styles.cardSubtitle}>
        {item.material_type || "Tipo de material no especificado"}
      </Text>
      <Text style={styles.cardSubtitle}>
        Pertenece a la orden: {item.order_id || "Orden no especificada"}
      </Text>

      <View style={styles.detailContainer}>
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
          Estado del Suministro: {item.supply_status || "No especificado"}
        </Text>
      </View>
    </TouchableOpacity>
  );

  return (
    <View style={styles.container}>
      <Text style={styles.title}>
        {id_sub_warehouse
          ? `Suministros del Subalmacén ${id_sub_warehouse}`
          : "Suministros"}
      </Text>

      <TextInput
        style={styles.searchInput}
        placeholder="Buscar suministros..."
        placeholderTextColor="gray"
        value={searchText}
        onChangeText={(text) => setSearchText(text)} // Actualiza el texto de búsqueda
      />

      {loading ? (
        <ActivityIndicator size="large" color="#4CAF50" />
      ) : (
        <FlatList
          data={filteredSuministros}
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
  container: {
    flex: 1,
    padding: 20,
    backgroundColor: "rgb(33, 37, 41)",
  },
  title: {
    fontSize: 24,
    fontWeight: "bold",
    marginBottom: 20,
    color: "#fff",
  },
  searchInput: {
    backgroundColor: "white",
    borderRadius: 10,
    padding: 10,
    marginBottom: 20,
    fontSize: 16,
    color: "black",
  },
  card: {
    padding: 15,
    marginBottom: 15,
    backgroundColor: "#fff",
    borderRadius: 10,
    elevation: 3,
    shadowColor: "#000",
    shadowOpacity: 0.1,
    shadowRadius: 5,
    shadowOffset: { width: 0, height: 2 },
  },
  cardTitle: {
    fontSize: 20,
    fontWeight: "bold",
    color: "#4CAF50",
    marginBottom: 5,
  },
  cardSubtitle: {
    fontSize: 16,
    fontWeight: "600",
    color: "#777",
    marginBottom: 10,
  },
  detailContainer: {
    marginTop: 10,
  },
  cardText: {
    fontSize: 14,
    color: "#333",
    marginBottom: 5,
  },
  emptyText: {
    textAlign: "center",
    fontSize: 16,
    color: "gray",
    marginTop: 20,
  },
});
