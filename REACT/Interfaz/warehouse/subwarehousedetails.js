import React from 'react';
import { View, Text, StyleSheet, TouchableOpacity } from 'react-native';

export default function SubWarehouseDetails({ route, navigation }) {
  const { id, location } = route.params; // Recibe los parámetros de navegación

  const handleNavigation = (option) => {
    if (option === 'Materiales') {
      navigation.navigate('MaterialesScreen', { id });
    } else if (option === 'Órdenes') {
      navigation.navigate('OrdersScreen', { id });
    } else if (option === 'Suppliers') {
      navigation.navigate('SuppliersScreen', { id });
    } else if (option === 'Transacciones') {
      navigation.navigate('TransaccionesScreen', { id });
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Detalles del Subalmacén</Text>
      <Text style={styles.text}>ID: {id}</Text>
      <Text style={styles.text}>Ubicación: {location}</Text>

      <View style={styles.menuContainer}>
        <TouchableOpacity
          style={styles.menuButton}
          onPress={() => handleNavigation('Materiales')}
        >
          <Text style={styles.menuText}>Materiales</Text>
        </TouchableOpacity>

        <TouchableOpacity
          style={styles.menuButton}
          onPress={() => handleNavigation('Órdenes')}
        >
          <Text style={styles.menuText}>Órdenes</Text>
        </TouchableOpacity>

  

        <TouchableOpacity
          style={styles.menuButton}
          onPress={() => handleNavigation('Transacciones')}
        >
          <Text style={styles.menuText}>Transacciones</Text>
        </TouchableOpacity>
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: 'rgb(33, 37, 41)',
    padding: 20,
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    color: 'white',
    marginBottom: 16,
  },
  text: {
    fontSize: 18,
    color: 'white',
    marginBottom: 8,
  },
  menuContainer: {
    marginTop: 20,
    width: '100%',
    alignItems: 'center',
  },
  menuButton: {
    backgroundColor: 'rgb(42, 126, 209)', // Azul para los botones
    paddingVertical: 15,
    paddingHorizontal: 30,
    borderRadius: 10,
    marginBottom: 15,
    width: '80%', // Botones más anchos
    alignItems: 'center',
  },
  menuText: {
    fontSize: 18,
    color: 'white',
    fontWeight: 'bold',
  },
});