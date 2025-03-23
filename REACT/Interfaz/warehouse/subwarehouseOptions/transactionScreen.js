import React, { useEffect, useState } from 'react';
import { View, Text, StyleSheet, FlatList, TextInput } from 'react-native';

export default function TransactionScreen({ route }) {
  const { id } = route.params; // Recibe el ID del subalmacén
  const [transactions, setTransactions] = useState([]);
  const [filteredTransactions, setFilteredTransactions] = useState([]); // Transacciones filtradas
  const [searchText, setSearchText] = useState(''); // Texto de búsqueda
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchTransactions();
  }, []);

  useEffect(() => {
    // Filtra las transacciones en función del texto de búsqueda
    const filtered = transactions.filter((item) =>
      item['ID Transacción'].toString().includes(searchText) ||
      item['Fecha de Transacción'].toLowerCase().includes(searchText.toLowerCase()) ||
      item['Tipo de Transacción'].toLowerCase().includes(searchText.toLowerCase()) ||
      item['Material'].toLowerCase().includes(searchText.toLowerCase()) ||
      item['Descripción del Material'].toLowerCase().includes(searchText.toLowerCase())
    );
    setFilteredTransactions(filtered);
  }, [searchText, transactions]);

  const fetchTransactions = async () => {
    try {
      const response = await fetch(
        `http://localhost/PROYECTO4B-1/phpfiles/react/transaction_api.php?id_sub_warehouse=${id}`
      );
      const data = await response.json();
      console.log('Transacciones recibidas:', data); // Verifica los datos en la consola
      setTransactions(data);
      setFilteredTransactions(data); // Inicializa las transacciones filtradas
    } catch (error) {
      console.error('Error obteniendo transacciones:', error);
    } finally {
      setLoading(false); // Cambia el estado de carga
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Transacciones del Subalmacén</Text>
      <TextInput
        style={styles.searchInput}
        placeholder="Buscar transacciones..."
        placeholderTextColor="gray"
        value={searchText}
        onChangeText={(text) => setSearchText(text)} // Actualiza el texto de búsqueda
      />
      {loading ? (
        <Text style={styles.loadingText}>Cargando...</Text>
      ) : filteredTransactions.length > 0 ? (
        <FlatList
          data={filteredTransactions}
          keyExtractor={(item) => item['ID Transacción'].toString()}
          renderItem={({ item }) => (
            <View style={styles.card}>
              <Text style={styles.cardTitle}>ID Transacción: {item['ID Transacción']}</Text>
              <Text style={styles.cardText}>Fecha: {item['Fecha de Transacción']}</Text>
              <Text style={styles.cardText}>Tipo: {item['Tipo de Transacción']}</Text>
              <Text style={styles.cardText}>Cantidad: {item['Cantidad']}</Text>
              <Text style={styles.cardText}>Ubicación: {item['Ubicación del Subalmacén']}</Text>
              <Text style={styles.cardText}>Material: {item['Material']}</Text>
              <Text style={styles.cardText}>Descripción: {item['Descripción del Material']}</Text>
            </View>
          )}
        />
      ) : (
        <Text style={styles.emptyText}>No hay transacciones disponibles</Text>
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