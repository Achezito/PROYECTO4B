import React, { useEffect, useState } from 'react';
import { View, Text, StyleSheet, FlatList, Dimensions } from 'react-native';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';

export default function WarehouseDetails({ route }) {
  const { id, name } = route.params;
  const [subWarehouses, setSubWarehouses] = useState([]);

  useEffect(() => {
    fetchSubWarehouses();
  }, []);

  const fetchSubWarehouses = async () => {
    try {
      const response = await fetch(`http://localhost/PROYECTO4B-1/phpfiles/react/sub_warehouse_api.php?id=${id}`);
      const data = await response.json();
      setSubWarehouses(data);
    } catch (error) {
      console.error('Error obteniendo subalmacenes:', error);
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Almacén: {name}</Text>
      <FlatList
        data={subWarehouses}
        keyExtractor={(item, index) => index.toString()}
        numColumns={2}
        columnWrapperStyle={styles.row}
        renderItem={({ item }) => (
          <View style={styles.card}>
            <Icon name="warehouse" size={40} color="rgb(42, 126, 209)" style={styles.icon} />
            <Text style={styles.cardTitle}>Subalmacén ID: {item.ID}</Text>
            <Text style={styles.cardText}>Ubicación: {item.Subalmacén}</Text>
          </View>
        )}
        ListEmptyComponent={
          <View style={styles.emptyContainer}>
            <Icon name="warehouse-off" size={50} color="white" />
            <Text style={styles.emptyText}>No hay subalmacenes disponibles</Text>
          </View>
        }
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: 'rgb(33, 37, 41)', // Fondo oscuro
    padding: 20,
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    color: 'white',
    marginBottom: 16,
    textAlign: 'center',
  },
  row: {
    justifyContent: 'space-between',
    marginBottom: 15,
  },
  card: {
    backgroundColor: 'white',
    borderRadius: 15, // Bordes más redondeados
    padding: 20,
    marginBottom: 15,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.3,
    shadowRadius: 5,
    elevation: 6,
    width: Dimensions.get('window').width / 2 - 30,
    alignItems: 'center', // Centra el contenido
  },
  icon: {
    marginBottom: 10,
  },
  cardTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: 'rgb(42, 126, 209)',
    marginBottom: 8,
    textAlign: 'center',
  },
  cardText: {
    fontSize: 14,
    color: 'rgb(33, 37, 41)',
    textAlign: 'center',
  },
  emptyContainer: {
    alignItems: 'center',
    marginTop: 50,
  },
  emptyText: {
    fontSize: 18,
    color: 'white',
    textAlign: 'center',
    marginTop: 10,
  },
});