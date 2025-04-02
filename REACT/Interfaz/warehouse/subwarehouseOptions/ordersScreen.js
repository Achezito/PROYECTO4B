import React, { useEffect, useState } from "react";
import { View, Text, StyleSheet, FlatList, TextInput } from "react-native";

export default function OrdersScreen({ route }) {
  const { id } = route.params; // Recibe el ID del subalmacén
  const [orders, setOrders] = useState([]);
  const [filteredOrders, setFilteredOrders] = useState([]); // Órdenes filtradas
  const [searchText, setSearchText] = useState(""); // Texto de búsqueda
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchOrders();
  }, []);

  useEffect(() => {
    // Filtra las órdenes en función del texto de búsqueda
    const filtered = orders.filter((item) => {
      const idOrden = item["ID Orden"]?.toString() || ""; // Maneja undefined
      const fechaOrden = item["Fecha de Orden"]?.toLowerCase() || ""; // Maneja undefined
      const estado = item["Estado"]?.toLowerCase() || ""; // Maneja undefined
      const proveedor = item["Proveedor"]?.toLowerCase() || ""; // Maneja undefined
      const ubicacion = item["Ubicación del Subalmacén"]?.toLowerCase() || ""; // Maneja undefined

      return (
        idOrden.includes(searchText.toLowerCase()) ||
        fechaOrden.includes(searchText.toLowerCase()) ||
        estado.includes(searchText.toLowerCase()) ||
        proveedor.includes(searchText.toLowerCase()) ||
        ubicacion.includes(searchText.toLowerCase())
      );
    });
    setFilteredOrders(filtered);
  }, [searchText, orders]);

  const fetchOrders = async () => {
    try {
      setLoading(true); // Activa el indicador de carga
      const response = await fetch(
        `http://localhost/PROYECTO4B-1/phpfiles/react/order_api.php?id_sub_warehouse=${id}`,
      );

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const data = await response.json();
      console.log("Órdenes recibidas:", data); // Verifica los datos en la consola

      // Verifica que los datos sean un arreglo
      if (Array.isArray(data)) {
        setOrders(data);
        setFilteredOrders(data); // Inicializa las órdenes filtradas
      } else {
        console.error("La respuesta no es un arreglo:", data);
        setOrders([]);
        setFilteredOrders([]);
      }
    } catch (error) {
      console.error("Error obteniendo órdenes:", error.message);
    } finally {
      setLoading(false); // Cambia el estado de carga
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Órdenes del Subalmacén</Text>
      <TextInput
        style={styles.searchInput}
        placeholder="Buscar órdenes..."
        placeholderTextColor="gray"
        value={searchText}
        onChangeText={(text) => setSearchText(text)} // Actualiza el texto de búsqueda
      />
      {loading ? (
        <Text style={styles.loadingText}>Cargando...</Text>
      ) : filteredOrders.length > 0 ? (
        <FlatList
          data={filteredOrders}
          keyExtractor={(item) => item["ID Orden"].toString()}
          renderItem={({ item }) => (
            <View style={styles.card}>
              <Text style={styles.cardTitle}>ID Orden: {item["ID Orden"]}</Text>
              <Text style={styles.cardText}>
                Fecha: {item["Fecha de Orden"]}
              </Text>
              <Text style={styles.cardText}>Estado: {item["Estado"]}</Text>
              <Text style={styles.cardText}>
                Proveedor: {item["Proveedor"]}
              </Text>
              <Text style={styles.cardText}>Cantidad: {item["Cantidad"]}</Text>
              <Text style={styles.cardText}>
                Ubicación: {item["Ubicación del Subalmacén"]}
              </Text>
            </View>
          )}
        />
      ) : (
        <Text style={styles.emptyText}>No hay órdenes disponibles</Text>
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: "rgb(33, 37, 41)",
    padding: 20,
  },
  title: {
    fontSize: 24,
    fontWeight: "bold",
    color: "white",
    marginBottom: 16,
    textAlign: "center",
  },
  searchInput: {
    backgroundColor: "white",
    borderRadius: 10,
    padding: 10,
    marginBottom: 20,
    fontSize: 16,
    color: "black",
  },
  loadingText: {
    fontSize: 18,
    color: "white",
    textAlign: "center",
  },
  card: {
    backgroundColor: "white",
    borderRadius: 10,
    padding: 15,
    marginBottom: 10,
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.2,
    shadowRadius: 4,
    elevation: 5,
  },
  cardTitle: {
    fontSize: 18,
    fontWeight: "bold",
    color: "rgb(42, 126, 209)",
    marginBottom: 5,
  },
  cardText: {
    fontSize: 16,
    color: "rgb(33, 37, 41)",
    marginBottom: 5,
  },
  emptyText: {
    fontSize: 18,
    color: "white",
    textAlign: "center",
    marginTop: 20,
  },
});
