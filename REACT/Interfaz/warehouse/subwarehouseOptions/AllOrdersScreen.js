import React, { useEffect, useState } from 'react';
import { FlatList, StyleSheet, Text, TextInput, View } from 'react-native';

export default function OrdersScreen() {
  const [orders, setOrders] = useState([]);
  const [filteredOrders, setFilteredOrders] = useState([]);
  const [searchText, setSearchText] = useState('');
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchOrders();
  }, []);

  useEffect(() => {
    const filtered = orders.filter((item) => {
      const idOrden = item.id_order?.toString() || '';
      const fechaOrden = item.created_at?.toLowerCase() || '';
      const estado = item.id_status?.toLowerCase() || '';
      const proveedor = item.proveedor?.toLowerCase() || '';
      const ubicacion = item.ubicacion?.toLowerCase() || '';

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
      setLoading(true);
      const response = await fetch('http://localhost/PROYECTO4B-1/phpfiles/react/order_api.php');
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const data = await response.json();
      console.log('Órdenes recibidas:', data);

      if (Array.isArray(data) && data.length > 0) {
        setOrders(data);
        setFilteredOrders(data);
      } else {
        console.error('No valid data received:', data);
        setOrders([]);
        setFilteredOrders([]);
      }
    } catch (error) {
      console.error('Error obteniendo órdenes:', error.message);
    } finally {
      setLoading(false);
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
        onChangeText={(text) => setSearchText(text)}
      />
      {loading ? (
        <Text style={styles.loadingText}>Cargando...</Text>
      ) : filteredOrders.length > 0 ? (
        <FlatList
          data={filteredOrders}
          keyExtractor={(item) => item.id_order ? item.id_order.toString() : 'default_key'}
          renderItem={({ item }) => (
            <View style={styles.card}>
              <Text style={styles.cardTitle}>ID Orden: {item.id_order}</Text>
              <Text style={styles.cardText}>Fecha: {item.created_at}</Text>
              <Text style={styles.cardText}>Estado: {item.id_status}</Text>
              <Text style={styles.cardText}>Cantidad: {item.supply_quantity}</Text>
              <Text style={styles.cardText}>Última Actualización: {item.updated_at}</Text>
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
    backgroundColor: 'rgb(33, 37, 41)',
    padding: 20,
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    color: 'white',
    marginBottom: 16,
    textAlign: 'center',
  },
  searchInput: {
    backgroundColor: 'white',
    borderRadius: 10,
    padding: 10,
    marginBottom: 20,
    fontSize: 16,
    color: 'black',
  },
  loadingText: {
    fontSize: 18,
    color: 'white',
    textAlign: 'center',
  },
  card: {
    backgroundColor: 'white',
    borderRadius: 10,
    padding: 15,
    marginBottom: 10,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.2,
    shadowRadius: 4,
    elevation: 5,
  },
  cardTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: 'rgb(42, 126, 209)',
    marginBottom: 5,
  },
  cardText: {
    fontSize: 16,
    color: 'rgb(33, 37, 41)',
    marginBottom: 5,
  },
  emptyText: {
    fontSize: 18,
    color: 'white',
    textAlign: 'center',
    marginTop: 20,
  },
});
