import React, { useEffect, useState } from 'react';
import { Alert, FlatList, StyleSheet, Text, TextInput, View } from 'react-native';

export default function TransactionScreen() {
  const [transactions, setTransactions] = useState([]);
  const [filteredTransactions, setFilteredTransactions] = useState([]); // Transacciones filtradas
  const [searchText, setSearchText] = useState(''); // Texto de búsqueda
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchTransactions();
  }, []);

  useEffect(() => {
    // Filtra las transacciones en función del texto de búsqueda
    const filtered = transactions.filter((item) => {
      const idTransaccion = item['ID Transaccion']?.toString() || '';
      const fechaTransaccion = item['Fecha de Transaccion']?.toLowerCase() || '';
      const tipoTransaccion = item['Tipo de Transaccion']?.toLowerCase() || '';
      const material = item['Material']?.toLowerCase() || '';
      const descripcion = item['Descripcion del Material']?.toLowerCase() || '';

      return (
        idTransaccion.includes(searchText.toLowerCase()) ||
        fechaTransaccion.includes(searchText.toLowerCase()) ||
        tipoTransaccion.includes(searchText.toLowerCase()) ||
        material.includes(searchText.toLowerCase()) ||
        descripcion.includes(searchText.toLowerCase())
      );
    });
    setFilteredTransactions(filtered);
  }, [searchText, transactions]);

  const fetchTransactions = async () => {
    try {
      setLoading(true); // Activa el indicador de carga
      const response = await fetch(
        `http://localhost/PROYECTO4B-1/phpfiles/react/transaction_api.php` // Elimina el `id` de la URL
      );

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const data = await response.json();
      console.log('Transacciones recibidas:', data); // Verifica los datos en la consola

      if (Array.isArray(data)) {
        setTransactions(data);
        setFilteredTransactions(data); // Inicializa las transacciones filtradas
      } else {
        console.error('La respuesta no es un arreglo:', data);
        Alert.alert('Error', 'No se pudieron cargar las transacciones.');
      }
    } catch (error) {
      console.error('Error obteniendo transacciones:', error);
      Alert.alert('Error', 'Hubo un problema al obtener las transacciones.');
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
          keyExtractor={(item, index) => index.toString()} // Se elimina el uso de 'ID Transacción'
          renderItem={({ item }) => (
            <View style={styles.card}>
              <Text style={styles.cardTitle}>ID Transacción: {item['ID Transaccion']}</Text>
              <Text style={styles.cardText}>Fecha: {item['Fecha de Transaccion']}</Text>
              <Text style={styles.cardText}>Tipo: {item['Tipo de Transaccion']}</Text>
              <Text style={styles.cardText}>Cantidad: {item['Cantidad']}</Text>
              <Text style={styles.cardText}>Ubicación: {item['Almacen de destino']}</Text>
              <Text style={styles.cardText}>Material: {item['Material']}</Text>
              <Text style={styles.cardText}>Descripción: {item['Descripcion del Material']}</Text>
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
